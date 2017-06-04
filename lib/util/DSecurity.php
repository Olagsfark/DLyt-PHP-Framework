<?php

DStartScript();
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

//indicate request without ip
final class DSecurity {
    public static function init(){
        //check ip address
    }

    /**
     * The unique hash represents the whole system uniquely.
     * @return string
     */
    public static function uniqueHash(){
        return hash(DSettings::$ecryptionScheme['core'], DSettings::$globalName);
        //todo you should revise the uniqueness of this hash
    }
    public static function encryptTwoWay($key, $data, $cipher = MCRYPT_BLOWFISH){
        return mcrypt_encrypt($cipher, $key, $data, MCRYPT_MODE_CBC);
    }
    public static function encryptOneWay($data){
        return hash(DSettings::$ecryptionScheme['core'], $data);
    }
    public static function decrypt($key, $data, $cipher = MCRYPT_BLOWFISH){
        return mcrypt_decrypt($cipher, $key, $data, MCRYPT_MODE_CBC);
    }
    public static function doSqlInjectionCleanup(&$object){
        if(empty($object))
            return false;
        if(is_array($object)){
            foreach($object as $key => $val)
                $object[$key] = self::doSqlInjectionCleanup($val);
            return $object; //returning is kinda pointless since we're writing directly to $object's location
        }
        
        return addslashes(trim(htmlentities(strip_tags($object),ENT_QUOTES,'utf-8')));
        //removed htmlspecialchars
    }
    public static function detectShellScripts(){
        //
    }
    public static function detectShellExe(){
        //
    }
    public static function fixShellInfection(){
        //
    }
    public static function doBackup(){
        //create a DBackup class
    }
    public static function secureFolders(){
        //
    }
    public static function secureFileSystem(){
        //create a DFileSystem for this
    }

    
}
