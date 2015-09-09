
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

		socket.join(socketZone);

		if (socketZone in rooms == false) {
			//add the room to the rooms object, then add user to their respective room
			rooms[socketZone] = {};
		}
		
		rooms[socketZone][socketUserId] = JSON.stringify([socketUserId,socketZone,socketUserName,socketUserImage]);
		
		console.log(Object.keys(rooms[socketZone]));

		//emit updateActiveUsers event with the array of users in the same room as the socket
		io.sockets.in(socketZone).emit('updateUsers',rooms[socketZone]);

	});

	socket.on('disconnect',function() {
		//remove userId from their room, emit updateUsers event
		var room = rooms[socketZone];
		delete room[socketUserId];

		io.sockets.in(socketZone).emit('updateUsers',room[socketZone]);
	});

});

