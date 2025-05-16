#include <stdlib.h>
#include <stdio.h>

void main() {
	int r = rand();

	if (r % 2 == 0) {
		printf("%d is even\n",r);
	} else {
		printf("%d is odd\n",r);
	}
}
  
