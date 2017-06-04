<?php

DStartScript();

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

/**
 * loads and returns the specified model component
 * @param string $modelName the name of the model to load
 * @return \DModel
 */
function getModelContext($modelName) {
    $modelName .= 'Model';
    return new $modelName();
}

/**
 * The abstract view class
 */
abstract class DView extends DTemplate {

    protected $model;
    protected $viewName;

    function __construct($viewName) {
        parent::__construct();
        $this->viewName = $viewName;
    }

    protected function loadModel() {
        $this->viewName .= 'Model';
        $this->model = new $this->viewName();
    }

    protected function getModelContext($modelName) {
        $modelName .= 'Model';
        return new $modelName();
    }

    function __destruct() {
        switch ($this->vType) {
            case D_VIEW_USE_TEMPLATE:
                $this->dillute();
                break;
            case D_VIEW_USE_DEFAULT:
                //just do nufin
                break;
        }
    }

}

////Bootstrap functions for non-template usage
?>