<?php

DStartScript();

class testModel extends DModel {

    public $context = array();

    function __construct() {
        parent::__construct('test');
        $this->loadHandler(); //loads the handler

        $this->headTest();
        $this->test1();
    }

    private function headTest() {
        $tmp = DModel::getDOMBuilderInstance();
        $tmp->setTitle('Dry template-less page')
                ->addLinkData(array('rel' => 'stylesheet',
                            'href' => 'res/css/style.css'))
                ->addMetaData(array('charset' => 'UTF-8'))
                ->addMetaData(array('name' => 'viewport',
                            'content' => 'width=device-width, initial-scale=1.0')
                        );

        $this->context['headData'] = $tmp->build();
    }

    private function test1() {
        $this->context['name'] = 'Olagoke Adedamola Farouq';
        $this->context['text'] = 'My awesome cool flexible php framework<br/>';
        $this->context['test'] = $this->handler->context['test'];
    }

}
