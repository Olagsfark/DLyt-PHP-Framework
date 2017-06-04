<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
DStartScript();
DLoadScript('DDatabaseHelper');

/**
 * This package contains interfaces that allow for generic IO access across
 *  all IO interfaces. All database classes must extend this interface
 */
abstract class DAbstractDatabase {

    use DDatabaseHelper;

    /**
     *
     * @var mysqli mysqli handle 
     */
    protected static $dbHandle = null;
    /**
     *
     * @var mysqli tmp mysqli handle
     */
    protected static $lastDbHandle = null;
    protected static $lastQueryHandle = null;
    protected static $lastResult = array(),
            $lastQuery = '',
            $dbDriver = '',
            $preparedQueries = array();

    const DB_MYSQL = 0,
            DB_SQLITE = 1;
    const FETCH_ASSOC = 2,
            FETCH_ROW = 3,
            FETCH_OBJECT = 4,
            FETCH_PREPARED = 5;

    function __construct() {
        $this->dbH = $this;
    }

    public function query($statement, $fetchMode = DAbstractDatabase::FETCH_ASSOC) {
        self::$lastResult = array();
        if (empty($statement) || self::$dbHandle == null){
            self::$lastResult['failed'] = true;
            return self::$lastResult;
        }
        self::$lastDbHandle = self::$dbHandle;
        self::$lastQuery = $this->cleanQueryStr($statement);
        self::$lastQueryHandle = self::$lastDbHandle->query(self::$lastQuery);
        if (!self::$lastQueryHandle){
            self::$lastResult['failed'] = true;
            self::$lastResult['error'] = self::$lastDbHandle->error;
            return self::$lastResult;
        }
        if ($fetchMode != false)
            return $this->lastQueryResult($fetchMode);
        return self::$lastQueryHandle; //just return it raw!!
    }

    public function cleanQueryStr($sql) {
        return trim($sql,'\0\x0B');
    }
    
    private function lastQueryResult($fetchMode = DAbstractDatabase::FETCH_ASSOC) {
        $identifier = strtolower(substr(self::$lastQuery, 0, 6));
        if ($identifier == 'update' || $identifier == 'insert' || $identifier == 'delete') {
            self::$lastResult['failed'] = ($this->affectedRows() < 1) ? true : false;
            return self::$lastResult;
        } elseif ($identifier == 'select') {
            if(!self::$lastDbHandle || @self::$lastDbHandle->errno != 0){
                self::$lastResult['failed'] = true;
                return self::$lastResult;
            }
            switch ($fetchMode) {
                case DAbstractDatabase::FETCH_ASSOC:
                    self::$lastResult['result'] = $this->getAssocResults();
                    break;
                case DAbstractDatabase::FETCH_ROW:
                    self::$lastResult['result'] = $this->getRowResults();
                    break;
                case DAbstractDatabase::FETCH_OBJECT:
                    self::$lastResult['result'] = $this->getObjectResults();
                    break;
                default:
                    self::$lastResult['result'] = $this->getArrayResults();
                    break;
            }
            self::$lastResult['failed'] = false;
            return self::$lastResult;
        }
    }

    public function prepareQuery($queryID, $sql) {
        //
    }

    public function affectedRows() {
        return self::$lastDbHandle->affected_rows <= 0 ? false : self::$lastDbHandle->affected_rows;
    }

    public function numRows() {
        return mysqli_num_rows(self::$lastQueryHandle);
    }

    public function isGood() {
        return self::$lastDbHandle->connect_errno > 0 ? false : true;
    }

    public function executePreparedQuery($queryID, $valueList = array()) {
        //
    }

    /**
     * A convinience method for the inner DDatabaseHelper's i_build.
     * we can have multiple builder traits, this method simply checks the one 
     * in use and calls its i_build()
     */
    public function build() {
        $tmp = $this->i_build();
        //echo $tmp;
        return $this->query($tmp);
    }
    private function getAssocResults() {
        $i = 0;
        $dbc = array();
        while ($row = self::$lastQueryHandle->fetch_assoc()) {
            foreach (array_keys($row) as $key) {//walk through the array with the total keys
                $dbc[$i][$key] = $row[$key]; //populate the $db array with the correct keys
            }
            $i++;
        }
        return $dbc;
    }

    private function getRowResults() {
        $i = 0;
        $dbc = array();
        while ($row = self::$lastQueryHandle->fetch_row()) {
            foreach (array_keys($row) as $key) {//walk through the array with the total keys
                $dbc[$i][$key] = $row[$key]; //populate the $db array with the correct keys
            }
            $i++;
        }
        return $dbc;
    }

    /**
     * @deprecated since version 1.0
     * @return DArray
     */
    private function getObjectResults() {
        return mysqli_fetch_object(self::$lastQueryHandle);
    }

    /**
     * @deprecated since version 1.0
     * @return DArray
     */
    private function getArrayResults() {
        return mysqli_fetch_array(self::$lastQueryHandle, MYSQLI_BOTH);
    }

}
