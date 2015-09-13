
var io = require('socket.io').listen(3000);
	
var userCount = 0;
var rooms = {};
var socketDictionary = {};

//in socketUserInfo event, add an array to socketDictionary with correlation between userId -> socketId

io.sockets.on('connection',function(socket) {

	var userId;
	var userZone;
	var userName;
	var userImage;

	socket.on('socketUserInfo',function(data) {

		var jsonUserInfo = JSON.parse(data);

		userId = jsonUserInfo[0];
		userZone = jsonUserInfo[1];
		userName = jsonUserInfo[2];
		userImage = jsonUserInfo[3];

		socket.join(userZone);

		if (userZone in rooms == false) {
			rooms[userZone] = {};
		}

		rooms[userZone][userId] = JSON.stringify([userName,userImage]);
		socketDictionary[userId] = JSON.stringify([userId,socket.id]);

		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);

	});

	socket.on('privateChat',function(data) {




	});

	socket.on('disconnect',function() {
		delete rooms[userZone][userId];
		delete socketDictionary[userId];
		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);
	});

});

