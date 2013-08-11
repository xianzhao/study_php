<?php

$header =  <<<HEADER
GET /index.html HTTP/1.1
Host:192.168.10.101
\r\n
HEADER;

var_dump($header);

$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));           

socket_connect($socket, '192.168.10.101', 80);

socket_write($socket, $header, strlen($header));

$content = socket_read($socket, 4096);

socket_close($socket);

echo $content;

