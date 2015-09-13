
var io = require('socket.io').listen(3000);
	
var userCount = 0;
var rooms = {};
var socketDictionary = [];

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
		socketDictionary.push([userId,socket.id]);

		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);

		console.log(socketDictionary);
	});

	socket.on('privateChat',function(data) {
		/**
		 * data contains {User ID (user who is receiving the message)}
		 * Need to find the socketID for the given userID (store each socketID in a socketsDictionary object with correlations to userId -> socketId)
		 * emit the private message to the socketId with io.to(socketId).emit(privateMessage,{from,message}).
		 * also try socket.to(socketID).emit(privateMessage,{from,message});
		 */


	});

	socket.on('disconnect',function() {
		delete rooms[userZone][userId];
		delete socketDictionary[userId];
		console.log(socketDictionary);
		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);
	});

});

