#include <stdio.h>

void main() {
	int sum = 5000, iteractions = 0;

	do {
		sum+=101;
		iteractions++;
	} while (sum < 5000);
	printf("sum is %d after %d iteractions\n",sum,iteractions);

	sum = 5000; iteractions = 0;

	while (sum < 5000) {
		sum+=101;
		iteractions++;
	}
	printf("sum is %d after %d iteractions\n",sum,iteractions);
}
