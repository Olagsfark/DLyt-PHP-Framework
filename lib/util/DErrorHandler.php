<?php
DStartScript();
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */


/**
 * to get the complete list of errors in code, DErrorHandler::showAll();
 * this class buffers all errors in code, hence you are free to code without worrying 
 * about scary errors showing on the webpage. you can check if there is any error by checking 
 * DErrorHandler::hasError(); to get the array of errors, DErrorHandler::getErrorList().
 * if you want all your errors to be logged in an errorlog file, just set DSettings::$logErrorsToFile 
 * to the desired path of the log file.
 */
class DErrorHandler{
    
    public static function addErrorByKey($tag,$errorKey){
        self::addError(DMainLang::$errorConsts[$errorKey], $tag);
        //i dint check the array key cos i want the error(if at all) to be logged too
        return false; //the return false is for semantics
    }
    public static function hasError(){
        return !empty(self::$errorList);
    }
    public static function addError($errorStr,$tag){
        self::$errorList[$tag] = $errorStr; //doc continue here. add errorlog .setting set
        if(DSettings::$logErrorsToFile)
            error_log(sprintf(DMainLang::$templates['errorLogFormat'],$errorStr, $tag, date('Y-M-D:h:i:s')), 3, DSettings::$logErrorsToFile); //consider building a path managing class
    }
    public static function clear(){
        self::$errorList = array();
    }
    public static function hasErrorTag($tag){
        return array_key_exists($tag, self::$errorList);
    }
    public static function getErrorByTag($tag){
        if(array_key_exists($tag, self::$errorList))
             return self::$errorList[$tag];
    }
    public static function getErrorList(){
        return self::$errorList;
    }
    public static function flushAll(){
        array_walk(self::$errorList, '_DBTSRTriggerError');
        self::clear();
    }
    public static function showAll(){
        array_walk(self::$errorList, '_DBTSRTriggerError'); //beacuse php expected a 2nd error param
    }
    
    public static $errorList = array();
    //error consts are in mainLang
}
