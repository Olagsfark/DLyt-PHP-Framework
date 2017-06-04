<?php
startScript();
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */

class DTasks{
    private static $taskList = array(),
            $fileHandle;
    
    public static function init(){
        self::$fileHandle = new DFile('');
    }
    public static function taskInstance(){
        return new DTaskStruct;
    }
    public static function addTask($taskStruct){
        self::$fileHandle->setFileName($taskStruct->getName());
        $data = sprintf(DMainLang::$templates['taskData'],
                $taskStruct->getName(),
                $taskStruct->getInterval(),
                $taskStruct->getExecTimes(),
                $taskStruct->getTask(),
                time() + $taskStruct->getInterval() //next time (first time)
            );
        self::$fileHandle->write($data);
    }
    public static function getTask($taskName){
        //
    }
    public static function getTaskList(){
        //
    }
    public static function deleteTask(){
        //
    }
    private static function performTask(){
        //
    }
}
DTasks::init();

class DTaskStruct{
    private $name = '',
            $interval = 0.0,
            $taskStr = '',
            $execTimes = 0;
    
    public function setName($taskName){
        $this->name = $taskName;
    }
    public function setInterval($interval){
        if(!is_numeric($interval))
            trigger_error ("Task interval has to be numeric");
        $this->interval = $interval;
    }
    public function setTask($taskFuncName){
        $this->taskStr = $taskFuncName;
    }
    public function getName(){
        return $this->name;
    }
    public function getInterval(){
        return $this->interval;
    }
    public function getTask(){
        return $this->taskStr;
    }
    public function getExecTimes(){
        return $this->execTimes;
    }
    
}

/**
 * $task = DTasks::taskInstance();
 * $task->setName('Delete_Cache');
 * $task->setInterval(60*60*24); //every 24 hours
 * $task->setTask("function");
 * DTasks::addTask($task);
 * 
 *$task = DTasks::getTask("name");
 * $task->getName();
 * $task->getInterval();
 * $task->getTask();
 * $task->getExecTimes();
 * 
 * DTasks::deleteTask("name");
 * array<string> = DTasks::taskList();
 */

/*
 * name, interval, task, execTimes, nextTime = (time+interval)
 */

?>