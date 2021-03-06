
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

	socket.on('sendPrivateMessage',function(data) {
		/*
		 * data contains {User ID (user who is receiving the message)}
		 * Need to find the socketID for the given userID (store each socketID in a socketsDictionary object with correlations to userId -> socketId)
		 * emit the private message to the socketId with io.to(socketId).emit(privateMessage,{from,message}).
		 * also try socket.to(socketID).emit(privateMessage,{from,message});

		 * grab socket id by using the passed userID (receiver) and pulling the socketId correlated with this value
		 * emit to the socketID of receiver with the message, and the userID of the sender

		 * loop over socketDictionary and grab array with the key of the receiverID
		 * grab the socketID from the array (value 1)
		 * emit the message to that socket with its socketID
		 */		 

		 var messageInfo = JSON.parse(data);

		 var receiverUserId = messageInfo[0];
		 var message = messageInfo[1];

		 for(id in socketDictionary) {
		 	if (id == receiverUserId) {
		 		var socketInfo = JSON.parse(socketDictionary[id]);
		 		socket.to(socketInfo[1]).emit('privateMessage',JSON.stringify([userId,message])); // userID here is the sender ID (user id of this socket). So the receiver knows who from
		 	}
		 }


	});

	socket.on('disconnect',function() {
	
		if (userName != "undefined") {
			delete rooms[userZone][userId];
			console.log(userName + " disconnected");
		}
		delete socketDictionary[userId];
		io.sockets.in(userZone).emit('updateUsers',rooms[userZone]);
	});

});

