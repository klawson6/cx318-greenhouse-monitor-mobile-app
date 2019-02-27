#include <msp430.h>
#include <driverlib.h>
#include "sensorDriver.h"

unsigned char PIR_interruptFlag = 0;
unsigned char SW1_interruptFlag_ = 0;
#pragma vector = PORT1_VECTOR
__interrupt void P1_ISR(void)
{

 switch(__even_in_range(P1IV,P1IV_P1IFG7))
  {
     case P1IV_P1IFG3: 
      	PIR_interruptFlag = 1;
    	GPIO_clearInterrupt(GPIO_PORT_P1, GPIO_PIN3);
        break;
        
          case P1IV_P1IFG2: //It is SW1
      	SW1_interruptFlag_ = 1;
    	GPIO_clearInterrupt(GPIO_PORT_P1, GPIO_PIN2);
      break;
     
      default:
        break;
  }
}



int main(void)
{
    WDTCTL = WDTPW | WDTHOLD;               // Stop watchdog timer


    // Disable the GPIO power-on default high-impedance mode
    // to activate previously configured port settings
    PMM_unlockLPM5();
     initialiseSensors();
  
    //Enable interrupts
    __enable_interrupt();
    while(1)
    {
      if(PIR_interruptFlag == 1){
  
        signalMotion();
        PIR_interruptFlag = 0;
      }   
      
      if (SW1_interruptFlag_ == 1){
        toggleHeater();
        
      }
    }
}
