#include <stdio.h>

void main() {
	int i,j;

	for (i=0;i<1000;i++){
		j=0;
		while (j<1000){
			printf("i = %d and j = %d\n",i,j);
			if (i == 42){
				goto endgame;
			}
			j++;
		}
	}
endgame:
	printf("That's all folks!\n");
}
