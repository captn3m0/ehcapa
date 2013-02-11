<?php
set_time_limit(0);
ob_implicit_flush();

$address = '127.0.0.1';
$port = 9000;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $address, $port);
socket_listen($sock);

do {
  $msgsock = socket_accept($sock);
  if($msgsock===false)
  	break;
  do{
  	$buf = socket_read($msgsock, 2048, PHP_NORMAL_READ);
  	if($buf===false)
  		break;
  	echo $buf;
  }while(true);
  socket_close($msgsock);
}while(true);