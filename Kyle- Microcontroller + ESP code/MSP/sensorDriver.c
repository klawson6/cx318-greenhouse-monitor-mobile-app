#include <msp430.h>
#include <driverlib.h>
#include <driverlib.h>


//void initialiseSensors();
//void signalMotion();

/**
Initialize sensor GPIO pins
*/
void initialiseSensors(){
  // Set pins 1.3 & 1.4 as inputs with pulled down resistors.
  // i.e. Detected value is by default LOW, and HIGH when input is recieved. 
  // 1.3- Fan Toggle, 1.4 Heater Toggle
  GPIO_setAsInputPinWithPullDownResistor(GPIO_PORT_P1, GPIO_PIN3|GPIO_PIN4);
  
  //Set GPIO pin 5.0 as input for the motion sensor
  GPIO_setAsInputPinWithPullDownResistor(GPIO_PORT_P2, GPIO_PIN5);
  
  //Set GPIO pin 2.7 as pin for the output signal on motion detection
  GPIO_setAsOutputPin(GPIO_PORT_P2, GPIO_PIN7);
  GPIO_setOutputLowOnPin(GPIO_PORT_P2, GPIO_PIN7);
  
  //Set GPIO pins 1.5 and 1.6 as outputs for the signal that will control the fans and the heating pad
  GPIO_setAsOutputPin(GPIO_PORT_P1, GPIO_PIN5 | GPIO_PIN6);
  GPIO_setOutputLowOnPin(GPIO_PORT_P1, GPIO_PIN5 | GPIO_PIN6);
}

void toggleFans(int state){
  if(state){
    GPIO_setOutputHighOnPin(GPIO_PORT_P1,  GPIO_PIN6);
  } else {
    GPIO_setOutputLowOnPin(GPIO_PORT_P1,  GPIO_PIN6);
  }
}

void toggleHeater(int state){
  if(state){
    GPIO_setOutputHighOnPin(GPIO_PORT_P1,  GPIO_PIN5);
  } else {
    GPIO_setOutputLowOnPin(GPIO_PORT_P1,  GPIO_PIN5);
  }
}

void signalMotion(int state){
  if(state){
    GPIO_setOutputHighOnPin(GPIO_PORT_P2, GPIO_PIN7);
  } else {
    GPIO_setOutputLowOnPin(GPIO_PORT_P2, GPIO_PIN7);
  }
}


