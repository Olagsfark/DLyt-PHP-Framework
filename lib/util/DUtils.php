<?php
DStartScript();
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

/* 
 * This script contains a collection of utility classes
 * ----------------------
 * i intentionally dont check for errors like array existence, e.t.c cos i want the errors to be logged or shown
 */

final class DUtils{
    public static function init(){
        mt_srand((double) microtime() * 1000000); //seeding the randomization generator
        if (!empty(DSettings::$timeConfig['defaultTimeZone']))
            date_default_timezone_set(DSettings::$timeConfig['defaultTimeZone']);
    }
    public static function mkRandom($min = null,$max = null){
        return $max !== null && $min !== null ? mt_rand($min,$max) : $min !== null ? mt_rand($min) : mt_rand();
    }
    public static function hasInnerArray($arr){
        if(!is_array($arr) || empty($arr))
            return false;
        foreach($arr as $kval)
            if(is_array($kval))
                return true;
    }
}

class DArray{
    /**
     * This class eases the use of arrays.
     * Just pass it an array for a start, and use its methods to do various things
     * Especially useful for GLOBALS and authentication(sessions, cookies)
     * its also an extension of the nice and new ArrayObject class
     */
    protected $Object;
    private static $instance = null;
    public static function getInstance($array){
        /**
         * This method is especially useful in cases where one needs to quickly use the array in one line
         * E.g $filename = DArray::getInstance(explode('/',$filepath))->last()
         * @param type $array The array to use
         */
        return new DArray($array);
    }
    function __construct(&$array = array()) {
        $this->Object = $array;
    }
    function __toString() {
        print_r($this->Object);
        return '';
    }
    public function exchangeArray($newArray){
        $this->Object = $newArray;
    }
    public function keys(){
        return array_keys($this->Object);
        //the use of functions is to optimize performance
    }
    public function values(){
        return array_values($this->Object);
    }
    /**
     * @param mixed $key key of the of the array location
     * @param mixed $value value of the array location
     * @return \DArray returns DArray for coder to b able to use joint statements (flexibility)
     */
    public function add($key, $value){
        $this->Object[$key] = $value;
        return $this;
    }
    public function count(){
        return count($this->Object);
    }
    public function isEmpty(){
        return ($this->count() == 0) ? true : false;
    }
    public function firstValue(){
        return array_values($this->Object)[0];
    }
    public function firstKey(){
        $keys = array_keys($this->Object);
        return $keys[0];
    }
    /**
     * Gets an array location by the integer index
     * @param int $index the location index
     * @return mixed
     */
    public function getIndex($index){
        //in case its keys arent strings
        $keys = array_keys($this->Object);
        return isset($keys[$index]) ? $keys[$index] : false;
    }
    public function getValue($key){
        return isset($this->Object[$key]) ? $this->Object[$key] : false;
    }
    public function lastKey(){
        $keys = array_keys($this->Object);
        return end($keys);
    }
    public function lastValue(){
        return end($this->Object);
    }
    public function remove($key){
        unset($this->Object[$key]);
        return $this;
    }
    public function getValues($keys){
        $tmp = array();
        if(is_array($keys)){
            foreach($keys as $i)
                $tmp[] = $this->getValue($i);
            return $tmp;
        }
        return false;
    }
    public function hasKey($key){
        return isset($this->Object[$key]);  //do wont throw custom error
    }                                       //fix this bug
    public function hasAllKeys($keys){  //if you can find it
        foreach($keys as $i)
            if(!$this->hasKey($i))
                return FALSE;
        return true;
    }
    public function clear(){
        $this->Object = array();
        return $this;
    }
    public function merge($array){
        array_merge(array(&$this->Object, $array));
    }

    public function walk($functionName){
        array_walk($this->Object, $functionName);
    }
    function __destruct() { //possible removal
        unset($this);
        unset($this->Object);
    }
}





//DForm, DFormBuilder to DModel
//DRequestParser to DHandler --done in DRequest
//DUrl  to maintain generic and powerful url management --done
//DUploadManager to DHandler
//DTime to util
//add a more stable template plugin
//add a more stable php mailing plugin
//add a more stable php security system, Firephp
/*
 * DPagination in DHandler
 * DPagination{
 *  __construct(array('tableName' => $DBTableName,'rows' => array(title,day,name,...)), $buffer){
 *      this.numOfLinks = ceil(SqlQuery("count rows from this.$DBTableName") / this.buffer = buffer);
 *  }
 *  getBufferData($linkNumber){
 *      this.currentPage = $linkNumber
 *      this.dataList = SqlQuery("select [rows,] from this.$DBTableName limit $linkNumber,this.buffer");
 *  }
 */
