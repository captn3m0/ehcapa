require 'socket'  
 
puts "Ehcapa Server 0.1"  
server = TCPServer.new('127.0.0.1', '9000')
 
loop do
	socket = server.accept
	Thread.start do
		request=socket.readline #first line
		method,path,version  = request.split(/ /)
		headers=[]
		while line = socket.gets
			headers << line.chomp
			break if line =~ /^\s*$/
		end
		socket.puts "HTTP/1.1 200 OK\r\n"
		text="<html><head><title>#{path}</title><body>You sent the following headers: <br><pre>"
		for i in headers
			text+="#{i}\n"
		end
		text+="</pre></body></html>"
		socket.print "Content-Length: #{text.length}\r\n"
		socket.print "Content-Type: text/html\r\n"
		socket.print "Server: ehcapa (Ruby) 0.1\r\n\r\n"
		socket.print text
		socket.close
		socket.end
	end #thread ends
end #loop ends