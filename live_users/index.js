
var io = require('socket.io').listen(3000);
	
var userCount = 0;
var rooms = {};

io.sockets.on('connection',function(socket) {

	var userId;
	var userZone;
	var userName;
	var userImage;

	//rooms not working properly. attempt to implement own room functionality with filtering a master object,, TRY NAMESPACES

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

		console.log(rooms[userZone]);

		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);
	});

	socket.on('disconnect',function() {
		delete rooms[userZone][userId];

		console.log(rooms[userZone]);

		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);
	});

});

