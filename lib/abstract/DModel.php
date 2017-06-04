<?php

DStartScript();

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
DLoadScript('DTemplate');
/**
 * The abstract model class
 */

abstract class DModel{
    /**
     * @var DHandler DHandler handle
     */
    protected $handler;
    /**
     * @var DHeadBuilder DHeadBuilder handle
     */
    protected $headBuilder;
    /**
     *
     * @var string Model name
     */
    protected $modelName;
    /**
     * @var DTemplateAdapter template class handler
     */
    public $template;
    
    function __construct($modelName) {
        $this->modelName = $modelName;
    }
    protected function loadHandler() {
        $this->modelName .= 'Handler';
        $this->handler = new $this->modelName();
    }
    protected function loadTemplate() {
        $this->template = DTemplate::getTemplateAdapterInstance();
    }

    protected function getHandlerContext($handlerName) {
        $handlerName .= 'Handler';
        return new $handlerName();
    }
    /**
     * headBuilder abstractor method
     * @return \headBuilder
     */
    public static function getDOMBuilderInstance(){
        return new DDOMBuilder();
    }
    
    function __destruct() {
        //
    }
}

/**
 * This class is probably not gonna make it to master.
 * Never use this class in a page twice
 */
final class DDOMBuilder{
    
    private $buffer = '';
    private $buildArray = array();
    /*
     * @param array $linkInfo array containing stylesheet info. href,e.t.c
     */
    public function addLinkData($linkInfo){
        $this->buildArray['link'][] = $linkInfo;
        return $this;
    }
    public function addMetaData($metaInfo){
        $this->buildArray['metadata'][] = $metaInfo;
        return $this;
    }
    public function setTitle($title){
        $this->buildArray['title'] = $title;
        return $this;
    }
    public function build(){
        //metadata first
        $this->mkMetaStr();
        //build style string too
        $this->mkLinkStr();
        $this->buffer .= "<title>{$this->buildArray['title']}</title>";
        return $this->buffer;
    }
    public function __toString() {
        return $this->build();
    }
    private function mkLinkStr(){
        $tmp = '';
        //metadata first
        foreach($this->buildArray['link'] as $link){
            $tmp .= '<link ';
            foreach($link as $key=>$value){
                $tmp .= $key.'="'.$value.'" ';
            }
            $tmp .= '>';
        }
        $this->buffer .= $tmp;
    }
    private function mkMetaStr(){
        $tmp = '';
        //metadata first
        foreach($this->buildArray['metadata'] as $meta){
            $tmp .= '<meta ';
            foreach($meta as $key=>$value){
                $tmp .= $key.'="'.$value.'" ';
            }
            $tmp .= '>';
        }
        $this->buffer .= $tmp;
    }
}
