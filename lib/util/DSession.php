<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
DStartScript();

/**
 * This class manages session handling, its still very premature 
 * and primitive. but its working perfectly well and very secure.
 */
final class DSession{
    private static $config;
    const AUTH_KEY = 'dlyt_auth';
    private static $keysEncrypted = array();
    
    public static function start(){
        //self::setConfig();
        session_start();
        session_regenerate_id(); //delete old session files option
    }
    public static function setConfig(){
        if(!self::isSafe()){
            trigger_error(DMainLang::$errorConsts['sessionNotSafe']);
            return;
        }
        self::$config = session_get_cookie_params();
        self::$config['isHttpOnly'] = DSettings::$sessionConfig['isHttpOnly'];
        self::$config['sessionName'] = DSettings::$sessionConfig['sessionName'];
        self::$config['HTTPSSecure'] = DSettings::$sessionConfig['HTTPSSecure'];
        @ini_set('session.use_only_cookies',1);
        session_set_cookie_params(self::$config['lifetime'],
                self::$config['path'],
                self::$config['domain'],
                self::$config['HTTPSSecure'],
                self::$config['isHttpOnly']);
        session_name(self::$config['sessionName']);
    }
    
    public static function get($key){
        $data = isset($_SESSION[$key]) ? (isset(self::$keysEncrypted[$key]) ? DSecurity::decrypt($key, $_SESSION[$key]) : $_SESSION[$key]) : false;
        return DSecurity::doSqlInjectionCleanup($data);
    }
    /**
     * The method is special cos it allows you to store a session in an 
     * encrypted format and it decrypts t for you automatically when you 
     * need to access it again
     * @param string $key the session key
     * @param string $value the data to be saved in the session key
     * @param string $encrypt whether to two encrypt data in session
     */
    public static function add($key, $value, $encrypt = false){
        if($encrypt){
            self::$keysEncrypted[] = $key;
            $value = DSecurity::encryptTwoWay($key, $value);
        }
        $_SESSION[$key] = $value;
        return true;
    }
    public static function remove($key){
        unset($_SESSION[$key]);
    }
    public static function exists($key){
        return isset($_SESSION[$key]);
    }
    public static function close(){
        session_destroy();
    }
    public static function isGood(){
        return (session_status() == PHP_SESSION_ACTIVE);
    }
    /**
     * Secures the session data by creating a sort of digital 
     * signature. where the session name and unique request hash 
     * are used to to generate a session hash
     */
    public static function secureSign(){
        self::add(self::AUTH_KEY, self::sessionHash());
    }
    /**
     * generates a unique hash for the current session.
     * this kind of hash detects if during a session, browser parameters 
     * or ip address changes
     */
    public static function sessionHash(){
        return DSecurity::encryptOneWay(DRequest::getUniqueId().self::$config['sessionName']);
    }
    public static function isSafe(){ 
        $authVal = self::get(self::AUTH_KEY);
        if(!$authVal){
            self::secureSign(); //probably means first run
        } else {
            return $authVal == self::sessionHash();
        }
        return true;
    }
    
}
