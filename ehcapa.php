<?php
set_time_limit(0);
ob_implicit_flush();

$address = '127.0.0.1';
$port = 9000;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $address, $port);
socket_listen($sock);
register_shutdown_function(function(){
	socket_shutdown($sock);
});
do {
  $msgsock = socket_accept($sock);
  if($msgsock===false)
  	break;
  do{
  	$buf = @socket_read($msgsock, 2048);
  	if(!$buf)
  		break;
  	$pos=strpos($buf,"\r\n\r\n");
  	//This is where request header ends
  	$headers=explode("\r\n",substr($buf,0,$pos));
  	$body= substr($buf,$pos+4);
  	$requestPath=explode(' ',$buf)[1];
  	//Now we prepare to send the response
  	$text="<html><head><title>".$requestPath."</title></head><body><pre>";
    $text.="Your requested path is ".$requestPath."\n\nYou sent the following headers: \n";
    foreach($headers as $i=>$header){
      $text.="$header\n";
    }
    $text.="</pre></body></html>";
    $responseHeaders=[
      "Content-Length"=>strlen($text),
      "Content-Type"=>"text/html",
      "Server"=>"ehcapa 0.1",
      "X-Powered-By"=>"Node.JS"
    ];

    //Now we send the response headers
    socket_write($msgsock,"HTTP/1.1 200 OK\r\n");
    foreach($responseHeaders as $i=>$h)
    	socket_write($msgsock,"$i: $h\r\n");
   	socket_write($msgsock,"\r\n");//to separate the header and body
   	socket_write($msgsock,$text);
   	socket_close($msgsock);
  }while(true);
}while(true);