#include <msp430.h>
#include <driverlib.h>

 extern void initialiseSensors();
 extern void toggleFans(int state);
 extern void toggleHeater(int state);
 extern void signalMotion(int state);
