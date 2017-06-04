<?php

DStartScript();

/**
 * This class parses all data from and to the website
 *
 * @author Olagoke Adedamola Farouq
 */
class Parser {

    private $inputData = "";
    private $arrBuffer = array();
    private $type;
    private $result;
    private $errno, $lastError;

    function __construct($type = PARSER_RAW, $inputData = "") {
        $this->type = $type;
        $this->inputData = $inputData;
    }

    public function addBlock($block) {
        $this->arrBuffer[$block] = array();
        return $this;
    }

    public function addPair($block, $key, $value) {
        if (!isset($this->arrBuffer[$block]))
            return false;
        $this->arrBuffer[$block][$key] = $value;
        return $this;
    }

    private function addError($errstr) {
        $this->errno++;
        $this->lastError = $errstr;
        return false;
    }

    public function hasBlock($block) {
        return isset($this->arrBuffer[$block]);
    }

    public function hasPair($block, $key) {
        return isset($this->arrBuffer[$block][$key]);
    }

    /**
     * @param type $block
     * @return array An array containing the pair values and keys
     */
    public function getBlock($block) {
        return $this->hasBlock($block) ? $this->arrBuffer[$block] : false;
    }

    /**
     * @param type $block
     * @param type $key
     * @return string the pair value requested
     */
    public function getPairValue($block, $key) {
        return $this->hasPair($block, $key) ? $this->arrBuffer[$block][$key] : false;
    }

    public function getBlockList() {
        return array_keys($this->arrBuffer);
    }

    public function degenerate() {
        if (empty($this->inputData))
            return false;
        switch ($this->type) {
            case PARSER_JSON:
                $this->degenerate_json();
                break;
            case PARSER_RAW:
                $this->degenerate_raw();
                break;
        }
        return $this->arrBuffer;
    }

    private function degenerate_json() {
        $res = json_decode($this->inputData, true);
        if ($res == NULL || $res == false)
            $this->addError("Could not decode provided JSON data");
        $this->arrBuffer = $res; //make it null to when we have error
    }

    private function degenerate_raw() {
        $tmp = preg_split('/(\])|(;)/i', $this->inputData);
        unset($tmp[count($tmp) - 1]);
        $bufBlock = "";
        foreach ($tmp as $val) {
            $val = trim($val);
            if ($val[1] == '/') {
                if (empty($bufBlock))
                    return trigger_error('Malformed Data info at block ending');
                $bufBlock = "";
            } elseif ($val[0] == '[') {
                if (!empty($bufBlock))
                    return trigger_error('Malformed Data info at block init');
                $bufBlock = substr($val, 1);
                $this->addBlock($bufBlock);
            } else {
                list($key, $value) = explode('=', $val);
                $this->addPair($bufBlock, $key, $value);
            }
        }
    }

    public function generate() {
        if (!$this->isValid() || $this->isEmpty())
            return false;
        switch ($this->type) {
            case PARSER_JSON:
                $this->generate_json();
                break;
            case PARSER_RAW:
                $this->generate_raw();
                break;
        }
        return $this->result;
    }

    private function generate_json() {
        $res = json_encode($this->arrBuffer);
        if ($res == false)
            $this->addError("Could not encode provided Parse Data");
        $this->result = $res;
    }

    private function generate_raw() {
        foreach ($this->arrBuffer as $k => $v) {
            $this->result .= "[$k]" . PHP_EOL;
            foreach ($v as $key => $val) {
                $this->result .= "$key=$val;" . PHP_EOL;
            }
            $this->result .= "[/$k]" . PHP_EOL;
        }
    }

    public function isValid() {
        return $this->errno == 0;
    }

    public function isEmpty() {
        return empty($this->arrBuffer);
    }

    public function setData($data) {
        $this->inputData = $data;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function removeBlock($block) {
        unset($this->arrBuffer[$block]);
    }

    public function removePair($block, $key) {
        unset($this->arrBuffer[$block][$key]);
    }

    public function toArray() {
        return $this->arrBuffer;
    }

    public function __toString() {
        print_r($this->arrBuffer);
    }

}

define("PARSER_JSON", 1);
define("PARSER_RAW", 2);
