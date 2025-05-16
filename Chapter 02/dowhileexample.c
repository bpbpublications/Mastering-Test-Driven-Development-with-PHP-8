#include <stdio.h>

void main(){
	int sum = 0;
	int iteractions = 0;

	 do {
		sum+=101;
		iteractions++;
	} while (sum < 5000);
	printf("%d is sum after %d iteractions\n",sum,iteractions);
}
