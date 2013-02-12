require 'socket'  
 
puts "Simple Echo Server V1.0"  
server = TCPServer.new('127.0.0.1', '9000')
 
socket = server.accept  
 
while line = socket.readline
  socket.puts line
  puts line
end