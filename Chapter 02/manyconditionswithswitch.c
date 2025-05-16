#include <stdio.h>

void main(int argc, char *argv[])
{
	char letter = 'x';
	
	if (argc > 1) {
		letter = *argv[1];
	}

	switch (letter){
		case 'a':
			printf("archive"); break;
		case 'b':
			printf("brief"); break;
		case 'c':
			printf("create"); break;
		case 'd':
			printf("delete"); break;
		case 'e':
			printf("extract"); break;
		case 'f':
			printf("format"); break;
		default:
			printf("command not found");
	}
	printf("\n");	
}
