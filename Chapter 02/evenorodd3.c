#include <stdlib.h>
#include <stdio.h>

void main() {
	int r = rand();
	char kind[4] = "odd";

	if (r % 2 == 0) {
		kind[0] = 'e';kind[1] = 'v';kind[2] = 'e';kind[3] = 'n'; 
	}
	printf("%d is %s\n",r,kind);	
}
  
