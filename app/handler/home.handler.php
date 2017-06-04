<?php

DStartScript();

class homeHandler extends DHandler {

    function __construct() {
        parent::__construct();
        $this->loadDatabase('DMYSQLDatabase');
        $this->test2();
    }

    public function test() {
        $tmp = $this->db->select('COUNT(*) as count')
                ->from('test')
                ->extra('order by id asc')
                ->build();
        
        $this->context['cm'] = ($tmp['failed']) ? $tmp['error'] : 'success';
    }

    public function test2() {
        $tmp = $this->db->update('test')
                ->set(
                        array('email' => 'farouq@gmail.com', 'phone' => '09084323')
                )
                ->where("`id`=19")
                ->build();

        $this->context['cm'] = ($tmp['failed']) ? $tmp['error'] : 'success';
    }
    public function test3() {
        $tmp = $this->db->insert(array('Olagoke Adedamola','09066862017','olagokedammy@gmail.com','/CURTIME()'))
                ->into('test',array('name', 'phone', 'email','moment'))
                ->build();

        $this->context['cm'] = ($tmp['failed']) ? $tmp['error'] : 'success';
    }


}
