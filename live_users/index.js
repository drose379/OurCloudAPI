var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get("/",function(request,response) {
	response.sendFile(__dirname + "/index.html");
});

io.on('connection',function(socket) {
	console.log("Connection Made!");
});


http.listen(3000,function() {
	console.log("Listening on 3000");
});