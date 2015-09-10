
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var socketUserId;
	var socketZone;
	var socketUserName;
	var socketUserImage;

	socket.on('userInfo',function(data) {
		console.log(io.sockets.sockets.length);

		var userData = JSON.parse(data);

		socketUserId = userData[0];
		socketZone = userData[1];
		socketUserName = userData[2];
		socketUserImage = userData[3];

		socket.join(socketZone);

		if (socketZone in rooms == false) {
			rooms[socketZone] = {};
		}
		
		rooms[socketZone][socketUserId] = JSON.stringify([socketUserId,socketZone,socketUserName,socketUserImage]);

		io.sockets.in(socketZone).emit('updateUsers',JSON.stringify(rooms[socketZone]));

	});

	socket.on('disconnect',function() {
		console.log("Socket disconnected");
		console.log(io.sockets.sockets.length);
		socket.leave(socketZone);

		var room = rooms[socketZone];
		delete room[socketUserId];

		io.sockets.in(socketZone).emit('updateUsers',JSON.stringify(room[socketZone]));
	});

});

