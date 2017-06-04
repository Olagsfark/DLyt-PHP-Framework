<?php

/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 */
DStartScript();

/**
 * This class acts as a high level abstract database access wrapper.
 */
trait DDatabaseHelper {

    private $curOp = ''; //1=select,2=insert,3=update,4=delete
    private $buffer = array();
    private $build = '';
    protected $dbH;

    /**
     * @param type $fields
     * @return DDatabaseHelper
     */
    public function select($fields) {
        $this->curOp = 'select';
        $this->buffer['select'] = is_array($fields) ? '`'.implode("`,`", $fields).'`' : $fields;
        return $this;
    }

    /**
     * @return DDatabaseHelper
     */
    public function delete() {
        $this->curOp = 'delete';
        return $this;
    }

    /**
     * @param type $table
     * @return DDatabaseHelper
     */
    public function update($table) {
        $this->curOp = 'update';
        $this->buffer['update'] = $table;
        return $this;
    }

    /**
     * @param type $values
     * @return DDatabaseHelper
     */
    public function insert($values) {
        $this->curOp = 'insert';
        $tmpFunc = function(&$str) {
            $str = $str[0] == '/' ? substr($str, 1) : "'$str'";
        };
        array_walk($values, $tmpFunc);
        $this->buffer['insert'] = implode(",", $values);
        return $this;
    }

    /**
     * @param type $table
     * @return DDatabaseHelper
     */
    public function into($table, $fields) {
        $this->buffer['into'] = $table;
        $this->buffer['fields'] = implode("`,`", $fields);
        return $this;
    }

    /**
     * @param type $table
     * @return DDatabaseHelper
     */
    public function from($table) {
        $this->buffer['from'] = $table;
        return $this;
    }

    /**
     * 
     * @param type $condition
     * @return DDatabaseHelper
     */
    public function where($condition) {
        $this->buffer['where'] = $condition;
        return $this;
    }

    public function extra($extra) {
        $this->buffer['extra'] = $extra;
        return $this;
    }

    /**
     * @param type $fields
     * @return DDatabaseHelper
     */
    public function set($fields) {
        if (is_array($fields)) {
            $tmp = '';
            $c = 0;
            foreach ($fields as $k => $v) {
                $tmp .= "`$k`=" . (($v[0] == '/') ? substr($v, 1) : "'$v'");
                if (++$c < count($fields))
                    $tmp .= ',';
            }
            $this->buffer['set'] = $tmp;
        } else
            $this->buffer['set'] = $fields;

        return $this;
    }

    public function i_build() {
        switch ($this->curOp) {
            case 'select':
                $this->build = "SELECT {$this->buffer['select']}" .
                        " FROM `{$this->buffer['from']}`".
                        ((isset($this->buffer['where'])) ? 
                                " WHERE {$this->buffer['where']}" : '') .
                        ((isset($this->buffer['extra'])) ?
                                " {$this->buffer['extra']}" : '');
                break;
            case 'delete':
                $this->build = "DELETE " .
                        " FROM `{$this->buffer['from']}`".
                        ((isset($this->buffer['where'])) ? 
                                " WHERE {$this->buffer['where']}" : '');
                break;
            case 'update':
                $this->build = "UPDATE `{$this->buffer['update']}` SET {$this->buffer['set']} "
                        . "WHERE {$this->buffer['where']}";
                break;
            case 'insert':
                $this->build = "INSERT INTO `{$this->buffer['into']}`(`{$this->buffer['fields']}`)".
                        " VALUES({$this->buffer['insert']})";
                break; //work on adding multiple value entries pls asap
        }
        $this->buffer = array();
        return $this->build;
    }

}
