<?php
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 * @package DIOHandler
 */

DStartScript();
/**
 * This class manages all mysql database business
 * Please note that this class is still experimental...infact, its not designed yet to be used fully
 */
DLoadScript('DAbstractDatabase');

class DMYSQLDatabase extends DAbstractDatabase{
    /**
     * this method lets you connect using the already set database config or, your another set of 
     * config. useful for working with multiple database users
     * @param type $dbInfoArray
     * @return \DMYSQLDatabase
     */
    public static function connect($dbInfoArray = array()) {
        $configInfo = DSettings::$databaseConfig['dbConnectionInfo'];
        self::connect_inner(!isset($dbInfoArray['host']) ? $configInfo['host'] : $dbInfoArray['host'],
                !isset($dbInfoArray['user']) ? $configInfo['user'] : $dbInfoArray['user'],
                !isset($dbInfoArray['password']) ? $configInfo['password'] : $dbInfoArray['password'],
                !isset($dbInfoArray['database']) ? (empty($configInfo['database']) ? null:$configInfo['database']) : $dbInfoArray['database']);
        self::$dbDriver = DAbstractDatabase::DB_MYSQL;
        return new DMYSQLDatabase();
    }
    private static function connect_inner($host, $user, $password, $database){
        try{
            self::$dbHandle = new mysqli($host, $user, $password, $database);
        } catch (Exception $ex) {
            die($ex->getMessage()); 
        }
    }
}