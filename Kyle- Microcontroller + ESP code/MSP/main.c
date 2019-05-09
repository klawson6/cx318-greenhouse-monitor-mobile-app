#include <msp430.h>
#include "sensorDriver.h"


void begin();

int main(void) {
  WDTCTL = WDTPW | WDTHOLD; // Stop watchdog timer
  
  // Disable the GPIO power-on default high-impedance mode
  PMM_unlockLPM5();
  initialiseSensors();
  
  // Begin main loop
  begin();
  
}

void begin() {
  while(1) {
    // If fans have been toggled on via app
    if(GPIO_getInputPinValue(GPIO_PORT_P1, GPIO_PIN3)){
      toggleFans(1);
    } else {
      toggleFans(0);
    }
    // If heater has been toggled on via app
    if(GPIO_getInputPinValue(GPIO_PORT_P1, GPIO_PIN4)){
      toggleHeater(1);
    } else {
      toggleHeater(0);
    }
    // If PIR detects motion
    if(GPIO_getInputPinValue(GPIO_PORT_P2, GPIO_PIN5)){
      signalMotion(1);
    }else {
      signalMotion(0);
    }
  }
}