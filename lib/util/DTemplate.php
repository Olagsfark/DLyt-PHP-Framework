<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
DStartScript();

abstract class DTemplate extends DBaseTemplate {

    function __construct() {
        //
    }

    public static function getTemplateAdapterInstance() {
        return new DTemplateAdapter();
    }

    /**
     * 
     * @param DTemplateAdapter $templateAdapter
     */
    protected function setTemplateAdapter($templateAdapter) {
        $this->hooks = $templateAdapter->templateLoad;
    }

}

final class DTemplateAdapter {

    public function addObject($templateId, $object, $paramVal) {
        $this->templateLoad[] = DTemplateHelper::generateObjectTemplateStr($templateId, $object, $paramVal);
        return $this;
    }

    public function addObjectMethod($templateId, $object, $methodName, $paramVal) {
        $this->templateLoad[] = DTemplateHelper::generateObjectMethodTemplateStr($templateId, $object, $methodName, $paramVal);
        return $this;
    }

    public function addFunction($templateId, $functionName, $paramVal) {
        $this->templateLoad[] = DTemplateHelper::generateFunctionTemplateStr($templateId, $functionName, $paramVal);
        return $this;
    }

    public function addString($templateId, $stringValue) {
        $this->templateLoad[] = DTemplateHelper::generateStringTemplateStr($templateId, $stringValue);
        return $this;
    }

    public function addExpression($templateId, $expressionValue) {
        $this->templateLoad[] = DTemplateHelper::generateExpressionTemplateStr($templateId, $expressionValue);
        return $this;
    }

    public function addList($templateId, $arrayList) {
        $templateObject = DTemplateHelper::generateListTemplateStr($templateId, $arrayList);
        $this->templateLoad[] = $this->listTempGenHelper($templateObject);
        return $this;
    }

    /**
     * The helper methods tries to decorate all array keys 
     * @param type $templateObject
     * @return array
     */
    private function listTempGenHelper($templateObject) { //pls review this method thoroughly
        if (!is_array($templateObject))
            return;
        $data = array();
        foreach ($templateObject as $k => $v) {
            if (is_array($v))
                $data["{$k}"] = $this->listTempGenHelper($v);
            else {
                $data['{{' . $k . '}}'] = $v;
            }
        }
        return $data;
    }

    public $templateLoad = array();

}

final class DTemplateHelper {

    public static function generateObjectTemplateStr($templateId, $object, $paramVal) {
        return array(
            self::decorationLiteral($templateId) => 'new ' . $object . (($paramVal === null) ? '()' : "($paramVal)")
        );
    }

    public static function generateObjectMethodTemplateStr($templateId, $object, $methodName, $paramVal) {
        return array(
            self::decorationLiteral($templateId) => $object . '->' . $methodName . (($paramVal === null) ? '()' : "($paramVal)")
        );
    }

    public static function generateListTemplateStr($templateId, $arrayList) {
        return array(
            self::decorationLiteral($templateId) => $arrayList
        );
    }

    public static function generateExpressionTemplateStr($templateId, $expressionValue) {
        return array(
            self::decorationLiteral($templateId) => $expressionValue
        );
    }

    public static function generateFunctionTemplateStr($templateId, $functionName, $paramVal) {
        return array(
            self::decorationLiteral($templateId) => $functionName . (($paramVal === null) ? '()' : "($paramVal)")
        );
    }

    public static function generateStringTemplateStr($templateId, $stringValue) {
        return array(self::decorationLiteral($templateId) => $stringValue);
    }

    private static function decorationLiteral($templId) {
        return '{{' . $templId . '}}';
    }

}


abstract class DBaseTemplate {

    protected function setTemplateResource($template) {
        $this->templ = DFPath('app.view.templates', $template, false);
        $this->vType = D_VIEW_USE_TEMPLATE; //Lets start the template engine for you
    }

    protected function dillute() {
        if (empty($this->hooks)) {
            trigger_error(DMainLang::$errorConsts['templateNoHook']);
            return false;
        }
        $this->loadFile();
        $this->procdTemplate = trim($this->rawFileData);
        $this->templateMix();
        echo $this->procdTemplate; //the diluted data
    }

    private function templateMix() {
        foreach ($this->hooks as $hook) {
            foreach ($hook as $id => $val) {
                if (mb_stristr($id, 'if:') !== false) { //if you dont find an if directive
                    $this->lexicalMix($id, $val);
                } elseif (mb_stristr($id, 'while:') !== false) { //if you dont find a loop directive
                    $this->loopMix($id, $val);
                } else {
                    $this->procdTemplate = str_replace($id, $val, $this->procdTemplate);
                    continue;
                }
            }
        }
    }

    private function loopMix($id, $val) {
        $endid = mb_substr($id, 0, 2) . '/' . substr($id, 2);
        $startLoopPos = mb_strpos($this->procdTemplate, $id);
        $endLoopPos = mb_strpos($this->procdTemplate, $endid);

        if (!$startLoopPos) {
            trigger_error(sprintf(DMainLang::$errorConsts['templateBadHook'], $id));
            return false;
        } else {
            $loopBlock = trim(mb_strcut($this->procdTemplate, $startLoopPos + mb_strlen($id), $endLoopPos - ($startLoopPos + mb_strlen($id))));
        }
        $cont = '';
        foreach ($val as $dirctvs) {
            $cont .= str_replace(array_keys($dirctvs), array_values($dirctvs), $loopBlock);
        }
        $this->procdTemplate = str_replace(array($loopBlock, $id, $endid), array($cont, '', ''), $this->procdTemplate);
    }

    private function lexicalMix($id, $val) {
        $elseTag = '{{else}}';
        $startPos = mb_strpos($this->procdTemplate, $id);
        $endTag = mb_substr($id, 0, 2) . '/' . substr($id, 2);
        $endTagLen = mb_strlen($endTag);
        $endPos = mb_strpos($this->procdTemplate, $endTag);

        if (!$startPos || !$endTag || !$endPos)
            trigger_error(sprintf(DMainLang::$errorConsts['templateBadHook'], $id));

        $cut = mb_strcut($this->procdTemplate, $startPos, ($endPos - $startPos) + $endTagLen);
        $elsePos = mb_strpos($cut, $elseTag) + mb_strlen($elseTag); //avoid false positives
        $nelsePos = mb_strpos($cut, $elseTag);
        if ($nelsePos) {
            $elseCut = mb_strcut($cut, $elsePos, (mb_strlen($cut) - $elsePos) - $endTagLen);
        }
        if ($val) { //isError
            if ($nelsePos) {
                $this->procdTemplate = str_replace(array($elseTag.$elseCut,$id, $endTag), '', $this->procdTemplate);
            } else {
                $this->procdTemplate = str_replace(array($id, $endTag), '', $this->procdTemplate);
            }
        } elseif ($nelsePos) {//echo htmlentities($cut);
            $this->procdTemplate = str_replace($cut, $elseCut, $this->procdTemplate);
        } else {
            $cut = mb_strcut($this->procdTemplate, $startPos, ($endPos - $startPos) + $endTagLen);
            $this->procdTemplate = str_replace($cut, '', $this->procdTemplate);
        }
    }

    private function loadFile() {
        $this->rawFileData = file_get_contents($this->templ);
    }

    private $rawFileData = '', $procdTemplate = '';
    protected $vType = '',
            $templ = '',
            $t_ = '';
    protected $hooks = array();

}
