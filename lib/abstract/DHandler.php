<?php
DStartScript();
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
/**
 * The abstract handler class
 */

abstract class DHandler{
    public $context = array();
    /**
     * The database instance handler
     * @var DAbstractDatabase database handle 
     */
    protected $db;
    
    function __construct() {
        //
    }
    public function loadDatabase($dbDriver){
        $this->db = class_exists($dbDriver) ? $dbDriver::connect() : null;
    }
    public function putExtra($key, $value){
        $this->context['extra'][$key] = $value;
        return true;
    }
    public function hasExtra($key){
        return !empty($this->context['extra'][$key]);
    }
    public function getExtra($key){
        return empty($this->context['extra'][$key]) ? false : $this->context['extra'][$key];
    }
    protected static function getFormHelperInstance($loginFormArray){
        return new DFormHelper($loginFormArray);
    }
    protected static function getLoginHelperInstance($keyArray = array()){
        return new DLoginHelper($keyArray);
    }
}

final class DFormHelper{
    private $formData = null;
    private $hiddenKey = '';
    
    public function __construct($loginFormArray, $hiddenKey = '') {
        unset($loginFormArray['action']);
        $this->formData = DArray::getInstance($loginFormArray);
        $this->hiddenKey = $hiddenKey;
    }
    public function setHiddenKey($key){
        $this->hiddenKey = $key;
    }
    public function setInputDbColumn(){ //array('input'=>'form input name', 'dbcolumn'=>''db column to get input'
        //
    }
    public function hasKey($key){
        return $this->formData->hasKey($key);
    }
    public function hasKeys($keysArray){
        return $this->formData->hasAllKeys($keysArray);
    }
    public function getKey($key){
        return $this->formData->getValue($key);
    }
    public function getValue($key){
        return $this->formData->getValue($key);
    }
    public function getValues($keysArray){
        return $this->formData->getValues($keysArray);
    }
    public function isEmpty(){
        if(!$this->formData)
            return true;
        if($this->formData->isEmpty())
            return true;
    }
    public function isSafe(){
        return !$this->isEmpty() && $this->getKey($this->hiddenKey) == DRequest::getUniqueId();
    }
    public function getValuesListStr(){ //mostly for debugging and sql query building
        return implode(',',  $this->formData->values());
    }
    public function getKeysListStr(){ //mostly for debugging and sql query building
        return implode(',',  $this->formData->keys());
    }
}

final class DLoginHelper{
    private $keys = array();
    
    public function __construct($keysArray = array()) {
        $this->keys = $keysArray;
        $this->secureLogin();
    }
    public function setSessionKeys($keysArray){
        $this->keys = $keysArray;
    }
    public function isLoggedIn(){
        foreach($this->keys as $key){
            if(!DSession::exists($key))
                return false;
        }
        return true;
    }
    public function secureLogin(){
        DSession::secureSign();
    }
    public function isSafe(){
        return (DSession::isGood() && DSession::isSafe());
    }
    public function getKey($key){
        return DSession::get($key);
    }
    public function addKey($key, $value, $encrypt = false){
        DSession::add($key, $value, $encrypt);
    }
    public function removeKey($key){
        DSession::remove($key);
    }
    public function logout(){
        foreach($this->keys as $key){
            DSession::remove($key);
        }
    }
}
