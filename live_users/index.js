
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var socketUserId;
	var socketZone;
	var socketUserName;
	var socketUserImage;

	console.log("Connection");

	socket.on('socketUserInfo',function(data) {
		var jsonUserInfo = JSON.parse(data);

		socketUserId = jsonUserInfo[0];
		socketZone = jsonUserInfo[1];
		socketUserName = jsonUserInfo[2];
		socketUserImage = jsonUserInfo[3];

		socket.join(socketZone);

		if (socketZone in rooms) {
			console.log("A user in the same zone has already joined");
		} else {
			console.log("You are the frist user in the zone, creating the item in the array");
			rooms[socketZone] = {};
		}

	});

	socket.on('disconnect',function() {

		socket.leave(socketZone);

		console.log("Socket Disconncted");
		
		socket.broadcast.to("UNH-Secure").emit('updateUsers',"Sent on disconnect");

	});

});

