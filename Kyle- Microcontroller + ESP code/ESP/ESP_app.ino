#include <BearSSLHelpers.h>
#include <CertStoreBearSSL.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiAP.h>
#include <ESP8266WiFiGeneric.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266WiFiScan.h>
#include <ESP8266WiFiSTA.h>
#include <ESP8266WiFiType.h>
#include <WiFiClient.h>
#include <WiFiClientSecure.h>
#include <WiFiClientSecureAxTLS.h>
#include <WiFiClientSecureBearSSL.h>
#include <WiFiServer.h>
#include <WiFiServerSecure.h>
#include <WiFiServerSecureAxTLS.h>
#include <WiFiServerSecureBearSSL.h>
#include <WiFiUdp.h>
#include <string.h>

// 192.168.43.241 is the IP to use!

const char* ssid = "Kyle's OnePlus 6t"; //your WiFi Name
const char* password = "star wars";  //Your Wifi Password
int fanPin = 16; // D0
int heaterPin = 05; // D1
int PIRin = 04; // D2
//int con = 00; // D3
//int PIRin = 02; // D4
WiFiServer server(1337);
void setup() {
  WiFi.begin(ssid, password);
  pinMode(fanPin, OUTPUT);
  pinMode(heaterPin, OUTPUT);
  //pinMode(con, OUTPUT);
  //pinMode(noCon, OUTPUT);
  pinMode(PIRin, INPUT);
  //digitalWrite(con, LOW);
  //digitalWrite(noCon, LOW);
  digitalWrite(fanPin, LOW);
  digitalWrite(heaterPin, LOW);
  // Start TCP server.
  server.begin();
}

void loop() {
  // put your main code here, to run repeatedly:
  if (WiFi.status() != WL_CONNECTED) {
    while (WiFi.status() != WL_CONNECTED) {
    //  digitalWrite(noCon, HIGH);
      delay(500);
    }
   // digitalWrite(noCon, LOW);
   // digitalWrite(con, HIGH);
  }

  WiFiClient client = server.available();

  if (client) {
    while (client.connected()) {
      if (client.available()) {
        char readChar = client.read();
        String command = "";

        while (readChar != '\n') {
          command += readChar;
          readChar = client.read();
        }
        if (command.equals("F_H")) {
          digitalWrite(fanPin, !digitalRead(fanPin));
        }
        else if (command == "H_H") {
          digitalWrite(heaterPin, !digitalRead(heaterPin));
        }
        if (digitalRead(PIRin)) {
          client.println("GET /alert HTTP/1.0");
          client.println();
//          client.print(String("GET /alert") + " HTTP/1.1\r\n" +
//                       "Host: NODEMCU\r\n" +
//                       "Connection: close\r\n" +
//                       "\r\n"
//                      );
        }
      }
    }
    client.stop();
  }
}
