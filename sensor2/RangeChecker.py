#!/usr/bin/env python3
import json
import requests 
import subprocess

#The file with the user details
file = '/home/srb16177/DEVWEB/2018/emaildata.json'

#Opens the desired file as a JSON:
with open(file) as json_file:
	data = json.load(json_file)
	
#Developer chosen values for the range
max_temp = 24
min_temp = 20

#Sets the URL for the GET request:
URL = "https://conrfield.com:1880/getTemp"

#GETs the Temp value with security turned off for the request
payload = requests.get(URL, verify=False)

#Makes the string payload into a number type
temp = float(payload.text.replace(';', ''))

#Creates a subprocess dependent on temp value to run a specific script for emailing the user
if temp>=max_temp:
	subprocess.Popen('php /home/srb16177/DEVWEB/2018/high-temp-email.php', shell = True, stdout = subprocess.PIPE) 
	
if	temp<=min_temp:
	subprocess.Popen('php /home/srb16177/DEVWEB/2018/low-temp-email.php', shell = True, stdout = subprocess.PIPE)
	
