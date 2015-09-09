var http = require('http');
var io = require('socket.io');

var server = http.createServer()

server.on('connection',function(socket) {

	console.log("Connected!");
	
	socket.on('disconnect',function() {
		console.log("Connection disconnected");
	});

	socket.on('testEvent',function(data) {
		console.log(data);
	});

});


server.listen(3000,function() {
	console.log("Listening on 3000");
});