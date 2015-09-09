var http = require('http');
var io = require('socket.io')(http);

var server = http.createServer(function(request,response) {
	response.send("Ok");
});

io.on('connection',function(socket) {

	console.log("Connected!");
	
	socket.on('disconnect',function() {
		console.log("Connection disconnected");
	});

	socket.on('testEvent',function(data) {
		console.log(data);
	});

});


server.listen(3000,function() {
	console.log("Listening on 3000");
});