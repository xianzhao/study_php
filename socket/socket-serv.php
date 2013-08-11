<?php
$server_str = "PHP TM 0.1";

$clients = array();

$socket_serv = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));

socket_bind($socket_serv, '127.0.0.1', 60819);

socket_listen($socket_serv);
//socket_set_nonblock($socket_serv);

while(true)
{
    $socket_conn = socket_accept($socket_serv);
    
    $pid = pcntl_fork();
    
    if ( $pid != 0 ) {
    
        socket_getpeername($socket_conn, $addr, $port);
        
        $clients[md5($addr.$port)] = $socket_conn;
        
        $str = <<<STR
        $server_str
        addr:$addr
        port:$port 
          
STR;

        socket_write($socket_conn, $str, strlen($str));
        
        $toPort = socket_read($socket_conn, 80, PHP_NORMAL_READ);
        
        
        while( ($content = socket_read($socket_conn, 80, PHP_NORMAL_READ)) != '') {
           $content = 'server:'.$content;
           socket_write($clients[md5($addr.$toPort)], $content, strlen($content)); 
        }
    }
    
    socket_close($socket_conn);

}
