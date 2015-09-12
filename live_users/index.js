
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
			console.log("Created new room for " + userZone);
		}

		io.sockets.in(userZone).emit('updateUsers',"User Joined! " + userName);

		userCount++;
		console.log(userCount);
	});

	socket.on('disconnect',function() {
		io.sockets.in(userZone).emit('updateUsers',"User Left! " + userName);

		userCount--;
		console.log(userCount);
	});

});

