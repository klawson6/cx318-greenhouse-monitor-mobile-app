

#include <msp430.h>
#include <driverlib.h>



unsigned char fansON = 0;
unsigned char heaterON = 0;

void initialiseSensors();
void signalMotion();


/**
Initialize sensor GPIO pins
*/
void initialiseSensors()
{
  
      //Set GPIO pin 1.2 as input with an interrupt for SW1
    GPIO_setAsInputPinWithPullDownResistor(GPIO_PORT_P1, GPIO_PIN2 );
    GPIO_selectInterruptEdge(GPIO_PORT_P1, GPIO_PIN2, GPIO_LOW_TO_HIGH_TRANSITION);
    GPIO_clearInterrupt(GPIO_PORT_P1, GPIO_PIN2);
    GPIO_enableInterrupt(GPIO_PORT_P1, GPIO_PIN2 );


    //Set GPIO pin 1.3 as input with an interrupt for the motion sensor
    GPIO_setAsInputPinWithPullDownResistor(GPIO_PORT_P1, GPIO_PIN3 );
    GPIO_selectInterruptEdge(GPIO_PORT_P1, GPIO_PIN3, GPIO_LOW_TO_HIGH_TRANSITION);
    GPIO_clearInterrupt(GPIO_PORT_P1, GPIO_PIN3);
    GPIO_enableInterrupt(GPIO_PORT_P1, GPIO_PIN3 );
    
    

    //Set GPIO pin 1.4 as pin for the output signal on motion detection
    GPIO_setAsOutputPin(GPIO_PORT_P1, GPIO_PIN4);
    GPIO_setOutputLowOnPin(GPIO_PORT_P1, GPIO_PIN4);

    //Set GPIO pins 5.1 and 5.2 as outputs for the signal that will control the fans and the heating pad
    GPIO_setAsOutputPin(GPIO_PORT_P1, GPIO_PIN5 | GPIO_PIN6);
    GPIO_setOutputLowOnPin(GPIO_PORT_P1, GPIO_PIN5 | GPIO_PIN6);


}

void signalMotion(){
  //Light up an LED connected to pin 1.4 to signal motion detected
  for(int i = 0;i<100;i++){
   GPIO_setOutputHighOnPin(GPIO_PORT_P1, GPIO_PIN4);
  __delay_cycles(10000);
   GPIO_setOutputLowOnPin(GPIO_PORT_P1, GPIO_PIN4);
  __delay_cycles(10000);

  }
}

  void toggleFans(){
    if(fansON == 0){
      GPIO_setOutputHighOnPin(GPIO_PORT_P1, GPIO_PIN6);
      fansON = 1;
    }
    else{
      GPIO_setOutputLowOnPin(GPIO_PORT_P1,GPIO_PIN6);
      fansON = 0;
    }
  }

  void toggleHeater(){
     if(heaterON == 0){
      GPIO_setOutputHighOnPin(GPIO_PORT_P1, GPIO_PIN5);
      heaterON = 1;
    }
    else{
      GPIO_setOutputLowOnPin(GPIO_PORT_P1, GPIO_PIN5);
      heaterON = 0;
    }
  }


