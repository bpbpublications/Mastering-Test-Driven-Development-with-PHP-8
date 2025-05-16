#include <stdlib.h>
#include <stdio.h>
#include <string.h>

void main() {
	int r = rand();
	char kind[4] = "odd";

	if (r % 2 == 0) {
		strcpy("even",kind);
	}
	printf("%d is %s\n",r,kind);	
}
  
