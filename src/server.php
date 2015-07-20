<?php
class Storage_Server {
    private $serv;
    
    public function __construct() {
        $this->serv = new swoole_server('0.0.0.0', 8001);
        $this->serv->set(array(
            'worker_num' => 1,
            'tasker_num' => 1, // todo，必发不影响同步序列
            'max_request' => 1000,
            'open_eof_check' => true,
            'package_eof' => "\r\n\r\n",
        ));
        
        $this->serv->on('Start', array($this, 'OnStart'));
        $this->serv->on('WorkerStart', array($this, 'OnWorkerStart'));
        $this->serv->on('Connect', array($this, 'OnConnect'));
        $this->serv->on('Receive', array($this, 'OnReceive'));
        $this->serv->on('Close', array($this, 'OnClose'));
    }
    
    public function onStart($serv) {
        printf("Master Process Start\n");
    }
    
    public function onWorkerStart($serv, $worker_id) {
        printf("Worker #%s Process Start\n", $worker_id);
    }
    
    public function onConnect($serv, $fd, $from_id) {
        printf("Client #%s Connect\n", $fd);
    }
    
    public function onReceive($serv, $fd, $from_id, $data) {
        printf("Server Receive Data[len=%s] from Cliend #%s data\n", strlen($data), $fd);
        
        $head_str = substr($data, 0, 32);
        $head_arr = unpack('Sid/Sversion/Llog_id/a16provider/Lmagic_num/Lreserved', $head);
        $body_str = substr($data, 32);
        $body_arr = msgpack_unpack($body_str);
    }
    
    public function onClose($serv, $fd, $from_id) {
        printf("Client #%s Close\n", $fd);
    }
}

$server = new Storage_Server();
