#include <stdio.h>

void main() {
	int sum;
	int iteractions;

	for (sum=0,iteractions=0;sum < 5000;iteractions++) {
		sum+=101;
	}
	printf("sum is %d after %d iteractions\n",sum,iteractions);
}
