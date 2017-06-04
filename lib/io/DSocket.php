<?php
/**
 * @author Olagoke Adedamola Farouq <olagokedammy@gmail.com>
 * @copyright (c) 2016, Olagoke Adedamola Farouq
 * @package DSocket
 */

DStartScript();
/**
 * The class provides networking capabilities like socket and curl networking package
 */

final class DSocket{
    private $socketHandle = null;
    private $host;
    private $port;
    public function __construct($host, $port) {
        $this->host = $host;
        $this->port = $port;
    }
    public function connect(){
        $addr = "tcp://$host:$port";
        $this->socketHandle = stream_socket_client($addr);
        if($this->socketHandle == false)
            throw new DSocketException("Failed to open a socket on $addr");
    }
    public function send($data){
        fwrite($this->socketHandle, $data);
    }
    public function recv(){
        return stream_get_contents($this->socketHandle);
    }
    public function isConnected(){
        return ($this->socketHandle == true);
    }
    public function __destruct() {
        fclose($this->socketHandle);
        parent::__destruct();
    }
}