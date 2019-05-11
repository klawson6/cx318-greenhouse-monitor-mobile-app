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
var pirFlag = 1;

$(document).ready(function () {

	$('#connectButton').click(function () {
		app.connect()
	})

	$('#disconnectButton').click(function () {
		app.disconnect()
	})

	$('#led1').click(function () {
		app.ledOn_F()
	})

	$('#led2').click(function () {
		app.ledOn_H()
	})
})

var app = {}
var esp;

app.connect = function () {

	$('#startView').hide()
	$('#connectingStatus').text('Connecting to ' + IPAddress)
	$('#connectingView').show()

	esp = new WebSocket('ws://192.168.43.241:81/', ['arduino']);

	esp.addEventListener('open', function (event) {
		window.console.log("Ready");
		app.connected();
	});

	esp.addEventListener('error', function (event) {
		window.console.log("Failed");
		app.rejected();
	});

	esp.addEventListener('message', function (event){
		if (event.data == "alert" && pirFlag){
			pirCount++;
			$('#test_para').text("PIR WORKS: "+pirCount)
			
			pirFlag = 0;
			setTimeout(function(){
				pirFlag = 1
			}, 5000);
		} else {
			window.console.log(event.data);
		}
	});
}

	app.connected = function () {
		console.log('Connected to ' + IPAddress);

		$('#connectingView').hide()
		$('#controlView').show()
	}

	app.rejected = function () {
		var errorMessage = 'Failed to connect to ' + app.IPAdress;
		console.log(errorMessage);
		navigator.notification.alert(errorMessage, function () { });
		$('#connectingView').hide()
		$('#startView').show()
	}
	 
app.ledOn_F = function() {
	app.sendString('F_H\n');
}
	 
app.ledOn_H = function() {
	app.sendString('H_H\n');
}

app.sendString = function(sendString) {

	console.log('Trying to send:' + sendString);

	esp.send(sendString);
}
 
app.disconnect = function() {
		esp.close();

	$('#controlView').hide()
	$('#startView').show()
}