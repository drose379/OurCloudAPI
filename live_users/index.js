var http = require('http');
var io = require('socket.io');

var server = http.createServer()

server.on('connection',function(socket) {
	console.log("Connection Made!");
	socket.join("Test");
});


server.listen(3000,function() {
	console.log("Listening on 3000");
});