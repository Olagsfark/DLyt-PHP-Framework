<?php

DStartScript();

DLoadLibrary('Parser');

class testHandler extends DHandler {

    function __construct() {
        parent::__construct();
        $this->test();
    }

    public function test() {
        $parse = new Parser(PARSER_JSON);
        $res = $parse->addBlock("command")
                ->addPair("command", 'hash', 'fknkj48wuj8ir')
                ->addPair("command", 'cmd-id', '7768')
                ->addBlock('signal')
                ->addPair('signal', 'type', 'server')
                ->addPair('signal', 'emit', 'device')
                ->addPair('signal', 'target', 'gateway')
                ->generate();
        $this->context['test'] = $res;
        
        $parse->setData($res);
        string $res1 = $parse->degenerate();
        
        print_r($res1);
    }

}
