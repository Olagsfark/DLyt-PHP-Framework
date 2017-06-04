<?php
startScript();
/**
 * This class handles the script buffering system
 * 
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
  */
final class cacheManager{
    
    private static $script = '';
    private static $config;

    public static function init(){
        self::$config = array(
            'cacheFolder' => DFPath('res.cache'),
            'cacheThreshold' => 3,
            'cachePrefix' => 'cache-'
        );
    }
    /**
     * The top part of the caching system
     */
    public static function startBuffer(){
        //naming issues here, pls fix file naming issues
        self::$script = new DFile(str_replace(array('?','/',','),'_',self::$config['cacheFolder'].self::$config['cachePrefix'].DRequest::SERVER('REQUEST_URI').'.cache'));
        if(self::$script->exists())
            if(self::$script->lastModifiedTime() > time()- self::$config['cacheThreshold']){
                include_once self::$script->handle;
                exit;
            }
        
        ob_start('ob_gzhandler');
    }
    /**
     * The end part of the caching system
     */
    public static function endBuffer(){
        self::$script->write(ob_get_contents().PHP_EOL.'<!--Cached page-->');
        ob_end_flush();//flush output
    }
}

cacheManager::init(); //this is VERY VERY WRONG!!

?>