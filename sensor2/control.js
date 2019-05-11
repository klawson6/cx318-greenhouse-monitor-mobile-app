"use strict"

var pirCount = 0;
var pirFlag = 1;

$("document").ready(function () {

    // $('#connectButton').click(function () {
    // 	control.connect();
    // });
    //
    // $('#disconnectButton').click(function () {
    // 	control.disconnect();
    // });

    // $('#led1').click(function () {
    // 	app.ledOn_F()
    // })
    //
    // $('#led2').click(function () {
    // 	app.ledOn_H()
    // })

    $("#controlContainer").hide();

    // Switch between fan on/off images
    $("#fanContainer").click(function () {
        control.toggleFans();
    });

    // Switch between heater off/on images
    $("#heaterContainer").click(function () {
        control.toggleHeater();
    });

    // Search animations
    $("#searchButton").click(function () {
        //control.connect();
        control.connected();
    });
});

var control = {};
var esp;

control.connect = function () {

    $("#searchButton").text("Searching");
    setInterval(function () {
        if ($("#searchButton").text() == "Searching...") {
            $("#searchButton").text("Searching");
        }
        $("#searchButton").append(".");
    }, 250);

    esp = new WebSocket('ws://192.168.43.241:81/', ['arduino']);

    esp.addEventListener('open', function (event) {
        window.console.log("Ready");
        control.connected();
    });

    esp.addEventListener('error', function (event) {
        window.console.log("Failed");
        control.rejected();
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
};

control.connected = function () {
    clearInterval();
    $("#controlHome").hide();
    $("#controlContainer").show();
};

control.rejected = function () {
    clearInterval();
    $('#searchtext').attr("text", "Could Not Find a Monitor, Try Agin");
    $("#searchButton").attr("text", "Search");
};

control.toggleFans = function () {
    var newSrc = ($("#fanImg").attr("src") === "img/fan0.png") ? "img/fan.gif" : "img/fan0.png";
    $("#fanImg").attr("src", newSrc);

    $("#fanText").text(newSrc == "img/fan.gif" ? "Ventilation On" : "Ventilation Off");

    control.sendString("F_H");
};

control.toggleHeater = function () {
    var newSrc = ($("#heaterImg").attr("src") === "img/heater.png") ? "img/heater.gif" : "img/heater.png";
    $("#heaterImg").attr("src", newSrc);

    $("#heatingText").text(newSrc == "img/heater.gif" ? "Heating On" : "Heating off");

    control.sendString("H_H");
};

control.sendString = function(sendString) {
	esp.send(sendString);
};
//
// app.disconnect = function() {
// 		esp.close();
//
// 	$('#controlView').hide()
// 	$('#startView').show()
// }