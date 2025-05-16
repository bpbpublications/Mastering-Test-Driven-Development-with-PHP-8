#include <stdio.h>

void main()
{
	int i;
	float sum;
	for (i=0;i<100;i++) {
		sum+=0.999;
	}
	printf("The sum is %f\n",sum);
}
