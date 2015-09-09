var http = require('http');
var io = require('socket.io').listen(3000);

var rooms = {}

io.sockets.on('connection',function(socket) {

	var socketUserId;
	var socketZone;

	socket.on('disconnect',function() {
		console.log("Socket disconnected!");
	});

	socket.on('userInfo',function(data) {
		//also make sure the socket joins this zone as a "room".

		var userData = JSON.parse(data);
		console.log(userData[0]);

	})

});

