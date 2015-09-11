
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var socketUserId;
	var socketZone;
	var socketUserName;
	var socketUserImage;

	socket.on('connect',function() {
		socket.join("UNH-Secure");
		socket.broadcast.to("UNH-Secure").emit('updateUsers',"Sent on connect");
	});

	socket.on('disconnect',function() {

		socket.leave(socketZone);
		
		socket.broadcast.to("UNH-Secure").emit('updateUsers',"Sent on disconnect");

	});

});

