#include <stdio.h>

void main(){
	int sum = 0;
	int iteractions = 0;

	while (sum < 5000) {
		sum+=101;
		iteractions++;
	}
	printf("%d is sum after %d iteractions\n",sum,iteractions);
}
