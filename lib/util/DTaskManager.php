<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
DStartScript();

DTaskManager::init();

/**
 * This functionality depends on how often the whole system is accessed
 * if not much access is made, lots of the tasks are gonna be missed
 */
final class DTaskManager extends DTask {

    private static $taskInstance = null, $dbHandle, $taskList = array();
    private static $taskDbFolder, $taskDbFile;
    private static $dbEmpty = true;

    private static function setConfig() {
        self::$taskDbFolder = DFPath('plugins.taskmanager.taskDb');
        self::$taskDbFile = '/task.db'; //this is default, not meant to be changed
    }
    public static function settaskDbFolder($folderPath) {
        self::$taskDbFolder = $folderPath;
    }

    public static function init() {
        self::setConfig();

        self::loadTaskDb();
        self::mkTaskList();
        self::execTaskList();
    }

    public static function getTaskInstance() {
        if (self::$taskInstance === null)
            self::$instance = new DTaskManager(); //in essence returns the useful DTask object

        return clone self::$taskInstance;
    }

    public static function addNewTask($DTaskInstance) {
        //
    }

    public static function deleteTask($taskName) {
        //
    }

    public static function clearTaskDb() {
        //
    }

    public static function dbEmpty() {
        return self::$dbEmpty;
    }

    public static function loadTaskDb() {
        //if the DFile extra isnt activated, the whole plugin wont work
        $tmp = new DFile(self::$taskDbFolder . self::$taskDbFile);
        if (!$tmp->exists() || !$tmp->size()) {
            self::$dbEmpty = true;
            return false;
        }

        self::$dbHandle = $tmp->readLines();
        foreach (self::$dbHandle as $i) {
            self::$taskList[] = self::getTaskInstance(); //here
        }
    }

    public static function mkTaskList() {
        //
    }

    public static function execTaskList() {
        //
    }

    /**
     * Returns an array of DTasks of task lists
     */
    public static function getTaskList() {
        //
    }

    public static function getTaskCount() {
        return;
    }

    public static function getTask($taskName) {
        //
    }

}

abstract class DTask {

    private $taskType;
    private $taskTime;
    private $taskId;
    private $taskName, $taskFunction;
    private $execTimes, $nextExecTime;

    public function __construct($taskName) {
        $this->setTaskName($taskName);
    }

    /**
     * task names must cannot contain more than one word
     * @param string The desired name of the new task
     */
    public function setTaskName($taskName) {
        if (!$this->isValidTaskName($taskName))
            return trigger_error("Task name cannot contain more than one word nor a comma(,) character!");
        $this->taskName = $taskName;
    }

    public function setTaskFunction($functionName) {
        if (!function_exists($functionName))
            return trigger_error("Function [$functionName] does not exist");
        $this->taskFunction = $functionName;
    }

    public function setTaskType($taskType = D_TASK_TYPE_ONCE) {
        if ($taskType !== D_TASK_TYPE_ONCE || $taskType !== D_TASK_TYPE_REPEAT)
            return trigger_error(DMainLang::$errorConsts['DTaskType']);
        $this->taskType = $taskType;
    }

    public function setTaskInterval($interval_time) { //in secs
        if ($this->taskType != D_TASK_TYPE_REPEAT)
            return trigger_error(DMainLang::$errorConsts['DTaskInterval']);
        $this->taskTime = $interval_time;
    }

    public function setTaskTimeout($timeout_time) { //in secs
        if ($this->taskType != D_TASK_TYPE_ONCE)
            return trigger_error(DMainLang::$errorConsts['DTaskTimeout']);
        $this->taskTime = $timeout_time;
    }

    public function getTaskName() {
        return $this->taskName;
    }

    public function getTaskType() {
        return $this->taskType;
    }

    public function getTaskId() {
        return $this->taskId;
    }

    public function getTaskInterval() {
        return $this->taskTime;
    }

    public function getTaskTimeout() {
        return $this->taskTime;
    }

    public function getTaskExecTimes() {
        return $this->execTimes;
    }

    public function getNextTaskExec() {
        return $this->nextExecTime;
    }

    public function isTaskExecTime() {
        if (time() >= $this->nextExecTime && (time() - $this->nextExecTime) < $this->taskTime)
            return true;
        return false;
    }

    private function isValidTaskName($name) {
        return (str_word_count($name) != 1 || strpos($name, ',') !== false) ? false : true;
    }

}

/*
 * 
 */