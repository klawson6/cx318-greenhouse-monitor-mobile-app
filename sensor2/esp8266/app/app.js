//
// Copyright 2015, Evothings AB
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//

var pirCount = 0;

$(document).ready(function() {

	$('#connectButton').click(function() {
		app.connect()
	})

	$('#disconnectButton').click(function() {
		app.disconnect()
	})

	$('#led1').click(function(){
		app.ledOn_F()
	})

	$('#led2').click(function(){
		app.ledOn_H()
	})	
})

var app = {}

app.PORT = 1337
app.socketId

app.connect = function() {

	var IPAddress = $('#IPAddress').val()
	//var IPAddress = "192.168.43.241";

	//console.log('Trying to connect to ' + IPAddress)
	$('#startView').hide()
	$('#connectingStatus').text('Connecting to ' + IPAddress)
	$('#connectingView').show()

	chrome.sockets.tcp.create(function(createInfo) {

		app.socketId = createInfo.socketId

		chrome.sockets.tcp.connect(
			app.socketId,
			IPAddress,
			app.PORT,
			connectedCallback)
	})

	chrome.sockets.tcp.onReceive.addListener(receiveCallback)

	function receiveCallback(info){
		if (info.data[0] == 'a'){
			$('#pir_msg').text('AAAAA PIR detected something! Count: ' + pirCount);
			pirCount++;
			setTimeout(function(){ $('#pir_msg').text('PIR searching...!');}, 3000);
		} else if (info.data[0] == '/'){
			$('#pir_msg').text('///// PIR detected something! Count: ' + pirCount);
			pirCount++;
			setTimeout(function(){ $('#pir_msg').text('PIR searching...!');}, 3000);
		} else {
		 	$('#pir_msg').text(info.data);
		 	setTimeout(function(){ $('#pir_msg').text('PIR searching...!');}, 3000);
		 }
	}

	function connectedCallback(result) {
	
		if (result === 0) {

			 console.log('Connected to ' + IPAddress)
					 
			 $('#connectingView').hide()
			 $('#controlView').show()
			
		}
		else {
			var errorMessage = 'Failed to connect to ' + app.IPAdress
			console.log(errorMessage)
			navigator.notification.alert(errorMessage, function() {})
			$('#connectingView').hide()
			$('#startView').show()
		}
	}
}

app.sendString = function(sendString) {

	console.log('Trying to send:' + sendString)	

	chrome.sockets.tcp.send (
		app.socketId,
		app.stringToBuffer(sendString),
		function(sendInfo) {

			if (sendInfo.resultCode < 0) {

				var errorMessage = 'Failed to send data'

				console.log(errorMessage)
				navigator.notification.alert(errorMessage, function() {})
			}
		}
	)
}

app.recieveString = function(){

}

app.ledOn_F = function() {
	app.sendString('F_H\n')
}

app.ledOn_H = function() {
	app.sendString('H_H\n')
}

app.disconnect = function() {

	chrome.sockets.tcp.close(app.socketId, function() {
		console.log('TCP Socket close finished.')
	})

	$('#controlView').hide()
	$('#startView').show()
}

app.stringToBuffer = function(string) {

	var buffer = new ArrayBuffer(string.length)
	var bufferView = new Uint8Array(buffer)
	
	for (var i = 0; i < string.length; ++i) {

		bufferView[i] = string.charCodeAt(i)
	}

	return buffer
}

app.bufferToString = function(buffer) {

	return String.fromCharCode.apply(null, new Uint8Array(buffer))
}