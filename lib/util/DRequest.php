<?php
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 * @package DRequest
 */
DStartScript();


/**
 * DRequest takes care of all request data entering the system
 */
class DRequest{
    /**
     * @var array a cleaned and secured $_GET held in DRequest::$GET
     */
    private static $GET;
    /**
     * @var array a cleaned and secured $_POST held in DRequest::$GET
     */
    private static $POST;
    /**
     * @var array a cleaned and secured $_SERVER held in DRequest::$GET
     */
    private static $SERVER;
    /**
     * @var string Holds the ip address of the request; 
     */
    private static $ipAddress = D_REQUEST_NO_IP;
    /**
     * @var mixed Holds the uniques ID for the request
     */
    private static $uniqueId = false;
    /**
     * @var bool litmus value to show if request is of post or get type
     */
    private static $isPost = true;
    /**
     * Returns $_SERVER index values
     * @param string $key The key of the $_SERVER array to return
     * @return mixed
     */
    public static function SERVER($key){
        return isset(self::$SERVER[$key]) ? self::$SERVER[$key] : false;
    }
    /**
     * 
     * @param int $inputType specify the global data array u want. INPUT_GET | INPUT_POST | INPUT_SERVER | INPUT_FILES
     * @return array array containing the input data
     */
    public static function GLOBALS($inputType){
        switch ($inputType){
            case INPUT_POST:
                return self::$POST;
            case INPUT_GET:
                return self::$GET;
            case INPUT_SERVER:
                return self::$SERVER;
            //case INPUT_FILE:
            //    return self::$POST;
        }
    }
    /**
     * Returns $_POST index values
     * @param string $key The key of the $_SERVER array to return
     * @return mixed
     */
    public static function POST($key){
        return isset(self::$POST[$key]) ? self::$POST[$key] : false;
    }
    /**
     * Returns $_GET index values
     * @param string $key The key of the $_SERVER array to return
     * @return mixed
     */
    public static function GET($key){
        return isset(self::$GET[$key]) ? self::$GET[$key] : false;
    }
    /**
     * Detects the IP address and returns it, if none is found, the method returns D_REQUEST_NO_IP
     * @return string
     */
    public static function getIPAddress(){
        if(self::$ipAddress != D_REQUEST_NO_IP)
            return self::$ipAddress;
        
        //for first time call
        if(!empty(self::$SERVER['REMOTE_ADDR'])){
            self::$ipAddress = self::$SERVER['REMOTE_ADDR'];  
        }else{
            foreach(array('HTTP_X_FORWARDED_FOR',
			'HTTP_CLIENT_IP',
			'HTTP_X_CLIENT_IP',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED') as $i){
                if(!empty(self::$SERVER[$i])){
                    self::$ipAddress = self::$SERVER[$i];
                }
            }
        }
        return filter_var(self::$ipAddress,FILTER_VALIDATE_IP); //possible bug?
    }
    /**
     * Detects the Internet protocol version and returns it
     * @return string
     */
    public static function getIPVersion(){
        return (self::getIPAddress() !== D_REQUEST_NO_IP) ? ((strpos(self::getIPAddress(),',')) ? D_REQUEST_TYPE_IPV4 : D_REQUEST_TYPE_IPV6) : false;
        //please revise this method on other versions release
    }
    /**
     * Detects the User Agent and returns it; if none is found, the method returns D_REQUEST_NO_USERAGENT
     * @return string
     */
    public static function getUserAgent(){
        return (!empty(self::$SERVER['HTTP_USER_AGENT'])) ? substr(self::$SERVER['HTTP_USER_AGENT'],50) : D_REQUEST_NO_USERAGENT;
    }
    public static function isAjax(){
        return (isset(self::$SERVER['HTTP_X_REQUESTED_WITH']) && self::$SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }
    public static function isAutomated(){
        //@todo we use possible list of tests
        //1. No user agent
        if(!self::getUserAgent())
            return true;
    }
    /**
     * The unique hash is used to prevent csrf attacks, form re-submission, remote form submission, and unique links per user
     * @return string
     */
    public static function getUniqueId(){
        if(!self::$uniqueId){ //lazy initialization
            //@todo device better ways to create unique request IDs
            self::$uniqueId = hash(DSettings::$ecryptionScheme['core'],self::getIPAddress().self::getUserAgent());
        }
        return self::$uniqueId;
    }
    public static function isForeignReferral(){
        //@todo still controversial
    }
    public static function isMobile(){
        $mDevs = '/(alcatel|amoi|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipaq|ipod
          |j2me|midp|mini|mmp|mobi|mobile|tecno|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-
          |smartphone|sony|symbian|t-mobile|telus|vodafone|ucweb|wap|webos|wireless|xda|xoom|zte)/i';
 
        return preg_match($mDevs, self::SERVER('HTTP_USER_AGENT')) ? true : false; 
    }
    
    public static function init(){
        self::$GET = &$_GET; //note the reference pass
        self::$POST = &$_POST; 
        self::$SERVER = &$_SERVER;
        
        if(get_magic_quotes_gpc())
            @set_magic_quotes_runtime(0);
        
        if(!empty(self::$GET)){
            DSecurity::doSqlInjectionCleanup(self::$GET);
            self::$isPost = false;
        }
        if(!empty(self::$POST))
            DSecurity::doSqlInjectionCleanup(self::$POST);
        
        DSecurity::doSqlInjectionCleanup(self::$SERVER);
        
        unset($GLOBALS['db_character_set']);
        unset($GLOBALS['cachedir']);
        //the reason for removing the action is because its not longer needed in the apps
        
    }
    private static function doXssCleanup(){
        //@todo need to work on this
        //i'm thinking this method may be pointless at the end
    }
    public static function header($command, $data){
        //comand = REDIRECT, REFRESH, CHANGE_MIME
        //$data = 'script_url','Xsecs','image/png...'
    }
    
    /*
    public static function POST($key, $validateFilter = FILTER_DEFAULT){  
        return filter_input(INPUT_POST,$key,$validateFilter); //use fully filter_ functions in future
    }
    public static function GET($key, $validateFilter = FILTER_DEFAULT){  
        return filter_input(INPUT_GET,$key,$validateFilter);
    }
    public static function SERVER($key, $validateFilter = FILTER_DEFAULT){  
        return filter_input(INPUT_SERVER,$key,$validateFilter);
    }
     * */
}


?>