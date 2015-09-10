
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
/*
		if (socketZone in rooms == false) {
			rooms[socketZone] = {};
		}
*/
		// only emit the current list to the socket joining, then later, update the list and broadcast to all sockets in the room besides the new one
		// since the socket just joining does not need to know about itself
		
		//socket.emit('updateUsers',JSON.stringify(rooms[socketZone]));
		
		rooms[socketZone][socketUserId] = JSON.stringify([socketUserId,socketZone,socketUserName,socketUserImage]);

		//socket.broadcast.to(socketZone).emit('updateUsers',JSON.stringify(rooms[socketZone]));

		io.sockets.in("UNH-Secure").emit('updateUsers',"Sent on connection");

	});

	socket.on('disconnect',function() {
		socket.leave("UNH-Secure");
		
		//socket.broadcast.to(socketZone).emit('updateUsers',JSON.stringify(rooms[socketZone]));
		io.sockets.in("UNH-Secure").emit('updateUsers',"Sent on disconnect");
	});

});

