
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var socketUserId;
	var socketZone;
	var socketUserName;
	var socketUserImage;

	socket.on('userInfo',function(data) {

		var userData = JSON.parse(data);

		socketUserId = userData[0];
		socketZone = userData[1];
		socketUserName = userData[2];
		socketUserImage = userData[3];

		socket.join("UNH-Secure");

		socket.broadcast.to("UNH-Secure").emit('updateUsers',"Sent on connection");

	});

	socket.on('disconnect',function() {
		socket.leave("UNH-Secure");
		
		socket.broadcast.to("UNH-Secure").emit('updateUsers',"Sent on disconnect");
	});

});

