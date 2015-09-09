var http = require('http').Server(app);
var io = require('socket.io')(http);

http.createServer(function(request,response) {

});

io.on('connection',function(socket) {
	console.log("Connection Made!");
});


http.listen(3000,function() {
	console.log("Listening on 3000");
});