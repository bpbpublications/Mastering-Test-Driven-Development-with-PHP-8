#include <stdio.h>

void main(int argc, char* argv[]) {
	char letter = 'x';
	
	if (argc > 1) {
		letter = *argv[1];
	}

	if (letter == 'a'){
		printf("archive");
	} else if (letter == 'b') {
		printf("brief");
	} else if (letter == 'c') {
		printf("create");
	} else if (letter == 'd') {
		printf("delete");
	} else if (letter == 'e') {
		printf("extract");
	} else if (letter == 'f') {
		printf("format");
	} else {
		printf("command not found");
	}
	printf("\n");
}

