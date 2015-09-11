
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var socketUserId;
	var socketZone;
	var socketUserName;
	var socketUserImage;

	console.log("Connection");

	io.sockets.emit('updateUsers',"Sent on connect");

	socket.on('disconnect',function() {

		socket.leave(socketZone);

		console.log("Socket Disconncted");
		
		socket.broadcast.to("UNH-Secure").emit('updateUsers',"Sent on disconnect");

	});

});

