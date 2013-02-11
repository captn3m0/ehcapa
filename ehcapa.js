var net = require('net');
var HOST = '127.0.0.1';//Listen on localhost
var PORT = 9000;//on port 9000

var listener=function(c){
  //c is a connection
  console.log("Client connected");
  c.on('end',function(){
    console.log("Disconnected");
  })

  c.on('data',function(x){
    var string=x.toString("ASCII")
    var data=string.split("\r\n\r\n");
    var requestHeaders=data[0].split("\r\n");
    var requestBody=data[1];
    var request=requestHeaders[0].split(' ');
    var requestMethod=request[0];
    var requestPath=request[1];
    var httpVersion=request[2];
    var text="<html><head><title>"+requestPath+"</title></head><body><pre>";
    text+="Your requested path is "+requestPath+"\n\nYou sent the following headers: \n";
    for(i in requestHeaders.slice(1)){
      console.log(requestHeaders[i]);
      text+=requestHeaders[i]+"\n";
    }
    text+="</pre></body></html>";
    var responseHeaders={
      "Content-Length":text.length,
      "Content-Type":"text/html",
      "Server":"ehcapa 0.1",
      "X-Powered-By":"Node.JS"
    }
    c.write("HTTP/1.1 200 OK\r\n");
    for(i in responseHeaders){
      c.write(i+": "+responseHeaders[i]+"\r\n");
    }
    c.write("\r\n");
    c.write(text);
    c.pipe(c);
    c.end();
  });

}
var server=net.createServer(listener);
server.listen(PORT,HOST);