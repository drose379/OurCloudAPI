var http = require('http');
var io = require('socket.io').listen(3000);

io.sockets.on('connection',function(socket) {
	console.log("Connection Made!");
	socket.join("Test");
});

