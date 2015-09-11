
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
		socketUserName = jsonUserInfo[2];
		userImage = jsonUserInfo[3];

		socket.join(socketZone);

		if (userZone in rooms == false) {
			rooms[userZone] = {};
		}

		rooms[userZone][userId] = JSON.stringify([socketUserImage,socket]);

		console.log(JSON.stringify(rooms[userZone]));
	});

	socket.on('disconnect',function() {
		socket.leave(userZone);

		delete rooms[userZone][userId];

		console.log(JSON.stringify(rooms[userZone]));
	});

});

