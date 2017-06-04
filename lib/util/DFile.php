<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 * @package DFIle
 */

DStartScript();

/**
     *This is a class that simplifies file access.
     * Needs a lot lot of improvement. include_directory search issue
     */
class DFile{
  
    public $handle = '';
    function __construct($filename) {
        $this->handle = $filename;
    }
    public function setFileName($filename){
        $this->handle = $filename;
    }
    public function lastAccessTime(){
        return fileatime($this->handle);
    }
    public function lastModifiedTime(){
        return filemtime($this->handle);
    }
    public function size(){
        return filesize($this->handle);
    }
    public function type(){
        return filetype($this->handle);
    }
    public function exists(){
        return file_exists($this->handle);
    }
    public function readAll(){
	//not the best as it opens and closes stream everytime
	//consider fopen & fclose
        return file_get_contents($this->handle); 
    }
    public function readLines(){ 
        return file($this->handle,FILE_IGNORE_NEW_LINES);
    }
    public function write($data){
        return file_put_contents($this->handle,$data);
    }
    public function append($data){
        return file_put_contents($this->handle,$data,FILE_APPEND);
    }
    public function close(){
        return unlink($this->handle);
    }
    
}