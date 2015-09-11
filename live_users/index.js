
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var userId;
	var userZone;
	var userName;
	var userImage;

	console.log("Connection");

	socket.on('socketUserInfo',function(data) {
		var jsonUserInfo = JSON.parse(data);

		userId = jsonUserInfo[0];
		userZone = jsonUserInfo[1];
		userName = jsonUserInfo[2];
		userImage = jsonUserInfo[3];

		if (userZone in rooms == false) {
			rooms[userZone] = {};
		}

		rooms[userZone][userId] = JSON.stringify([userName,userImage]);

		//broadcast the object with the new socket to the rest of the sockets in the room.
		socket.broadcast.emit('updateUsers',JSON.stringify(rooms));
	});

	socket.on('disconnect',function() {
		delete rooms[userZone][userId];

		socket.broadcast.emit('updateUsers',JSON.stringify(rooms));
	});

});

