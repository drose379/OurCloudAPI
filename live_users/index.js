
var io = require('socket.io').listen(3000);
	
var userCount = 0;

io.sockets.on('connection',function(socket) {

	var userId;
	var userZone;
	var userName;
	var userImage;

	console.log("Connection");

	//rooms not working properly. attempt to implement own room functionality with filtering a master object,, TRY NAMESPACES

	socket.on('socketUserInfo',function(data) {
		var jsonUserInfo = JSON.parse(data);

		userId = jsonUserInfo[0];
		userZone = jsonUserInfo[1];
		userName = jsonUserInfo[2];
		userImage = jsonUserInfo[3];


		io.sockets.in("testZone").emit('updateUsers',"User Joined! " + userName);

		userCount++;
		console.log(userCount);
	});

	socket.on('disconnect',function() {
		io.sockets.in("testZone").emit('updateUsers',"User Left! " + userName);

		userCount--;
		console.log(userCount);
	});

});

