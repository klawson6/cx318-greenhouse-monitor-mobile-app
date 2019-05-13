"use strict"

// Flag to delay PIR alerts
var pirFlag = 1;

var timeLookUp = {
    900: "24:00",
    1080: "03:00",
    1260: "06:00",
    0: "09:00",
    180: "12:00",
    360: "15:00",
    540: "18:00",
    720: "21:00"
};

var visLookUp = {
    UN: "Unknown",
    VP: "Very Poor- Less than 1 km",
    PO: "Poor- Between 1-4k m",
    MO: "Moderate- Between 4-10 km",
    GO: "Good- Between 10-20 km",
    VG: "Very Good- Between 20-40 km",
    EX: "Excellent- More than 40km"
};

var weatherLookUp = {
    NA: "Not available",
    0: "Clear night",
    1: "Sunny day",
    2: "Partly cloudy (night)",
    3: "Partly cloudy (day)",
    4: "Not used",
    5: "Mist",
    6: "Fog",
    7: "Cloudy",
    8: "Overcast",
    9: "Light rain shower (night)",
    10: "Light rain shower (day)",
    11: "Drizzle",
    12: "Light rain",
    13: "Heavy rain shower (night)",
    14: "Heavy rain shower (day)",
    15: "Heavy rain",
    16: "Sleet shower (night)",
    17: "Sleet shower (day)",
    18: "Sleet",
    19: "Hail shower (night)",
    20: "Hail shower (day)",
    21: "Hail",
    22: "Light snow shower (night)",
    23: "Light snow shower (day)",
    24: "Light snow",
    25: "Heavy snow shower (night)",
    26: "Heavy snow shower (day)",
    27: "Heavy snow",
    28: "Thunder shower (night)",
    29: "Thunder shower (day)",
    30: "Thunder"
};

// Sets up listeners when document loads
$("document").ready(function () {

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
        control.connect();
        //control.connected();
    });

    // Disconnect from the ESP8266
    $("#disconnectButton").click(function () {
        control.disconnect();
    });
});

// An instance of the control.js function
var control = {};
// An instance of the websocket to the ESP8266
var esp;
var currentVals;

control.connect = function () {

    // Tell the user it is searching for the ESP8266 websocket on the local network
    $("#searchButton").text("Searching");
    // Search animation
    var searchInterval = setInterval(function () {
        if ($("#searchButton").text() == "Searching...") {
            $("#searchButton").text("Searching");
        }
        $("#searchButton").append(".");
    }, 250);

    // Connect to the ESP8266 websocket on the local network.
    esp = new WebSocket('ws://192.168.43.241:81/', ['arduino']);

    // Listen for websocket readystate 'open' to continue with the established connection
    esp.addEventListener('open', function (event) {
        window.console.log("Ready");
        clearInterval(searchInterval);
        control.connected();
    });

    // Listen for the websocket readystate 'error' to inform the user of the failed connection and to try again
    esp.addEventListener('error', function (event) {
        window.console.log("Failed");
        clearInterval(searchInterval);
        control.rejected();
    });

    // Listen for the websocket state 'message' to handle in incoming message from the ESP8266
    esp.addEventListener('message', function (event) {
        // Only allow message (which are PIR alerts) every 5 seconds
        if (event.data == "alert" && pirFlag) {
            $('#pirText').text("PIR Detection at: " + new Date().toString());
            pirFlag = 0;
            setTimeout(function () {
                pirFlag = 1;
            }, 5000);
        } else {
            window.console.log(event.data);
        }
    });
};

// Change HTML to connected page
control.connected = function () {
    $("#controlHome").hide();
    $("#controlContainer").show();
    // $("#pirText").text("PIR Detection at: " + new Date().toString());

    // Use AJAX (Asynchronous JavaScript and XML) to request values from the SensorTag via app server
    // AJAX requests in use here are HTTP GET Requests
    currentVals = setInterval(function () {
        $.ajax({
            url: "https://conrfield.com:1880/getTemp", // Modified URL serves the required data
            // Callback function to handle the data returned from AJAX request
            success: control.setTemp,
            dataType: "text"
        });

        $.ajax({
            url: "https://conrfield.com:1880/getLight", // Modified URL serves the required data
            // Callback function to handle the data returned from AJAX request
            success: control.setLight,
            dataType: "text"
        });

        $.ajax({
            url: "https://conrfield.com:1880/getHumidity", // Modified URL serves the required data
            // Callback function to handle the data returned from AJAX request
            success: control.setHumidity,
            dataType: "text"
        });
    }, 5000); // Perform a request every 5 seconds

    $.ajax({
        url: "http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/310009?res=3hourly&key=2ece4ff9-19e3-44ff-86a9-0f720226167a",
        success: control.displayWeather,
        dataType: "json"
    });
};

// Callback function to parse the data returned from MetOffice Datapoint
control.displayWeather = function (data) {
    if (data !== null && data !== undefined) {
        var now = data.SiteRep.DV.Location.Period[0].Rep[0]; // Parse JSON structure to get to desired data
        window.console.log("Feels like: " + now.F + " °C\nTemperature: " + now.T + " °C\nVisibility: " + now.V + "\nUV Index: " + now.U);

        // Display information in HTML
        control.setWeatherHeading(now.$);

        $("#tempText").text("Temperature: " + now.T + " °C");
        $("#visText").text("Visibility: " + visLookUp[now.V]);
        $("#UVText").text("UV Index: " + now.U);
        $("#typeText").text("Weather: " + weatherLookUp[now.W]);
        $("#flText").text("Feels Like: " + now.F + " °C");
    }
};

// Sets a heading in the HTML for forecast
control.setWeatherHeading = function (time) {
    $("#weatherHeading").text("Forecast for " + timeLookUp[time] + " in Glasgow");
};

// Set the current temperature on the DOM
control.setTemp = function (data) {
    $("#currentTemp").text(data.slice(0, -1) + " °C");
};

// Set the current light on the DOM
control.setLight = function (data) {
    $("#currentLight").text(data.slice(0, -1) + " lux");
};

// Set the current temperature on the DOM
control.setHumidity = function (data) {
    $("#currentHumidity").text(data.slice(0, -1) + " %rH");
};

// Change HTML to failed connection page
control.rejected = function () {
    $('#searchtext').attr("text", "Could Not Find a Monitor, Try Agin");
    $("#searchButton").attr("text", "Search");
};

// Toggle the fans of the system off/on
control.toggleFans = function () {
    // Switch image from stationary fan to moving fan for off/on respectively
    var newSrc = ($("#fanImg").attr("src") === "img/fan0.png") ? "img/fan.gif" : "img/fan0.png";
    $("#fanImg").attr("src", newSrc);

    // Present an informative message of the fan on/off state
    $("#fanText").text(newSrc == "img/fan.gif" ? "Ventilation On" : "Ventilation Off");

    // Send a command to the ESP8266 to toggle the fans on/off via the established websocket
    control.sendString("F_H\n");
};

// Toggle the heater off the system off/on
control.toggleHeater = function () {
    // Switch image from stationary heater image to moving heater image for heater off/on respectively
    var newSrc = ($("#heaterImg").attr("src") === "img/heater.png") ? "img/heater.gif" : "img/heater.png";
    $("#heaterImg").attr("src", newSrc);

    // Present and informative message of the heater off/on state
    $("#heatingText").text(newSrc == "img/heater.gif" ? "Heating On" : "Heating off");

    // Send a command to the ESP8266 to toggle the heater on/off via the established websocket
    control.sendString("H_H\n");
};

// Send a String to the ESP8266 via the established websocket
control.sendString = function (sendString) {
    esp.send(sendString);
};

// Disconnect the websocket to the ESP8266
control.disconnect = function () {
    // Close websocket
    esp.close();

    clearInterval(currentVals);

    // Put the HTML elements back to start screen
    $("#controlContainer").hide();
    $("#controlHome").show();
    $("#searchButton").text("Search");
};