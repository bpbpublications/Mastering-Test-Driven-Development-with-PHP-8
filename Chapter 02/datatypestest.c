#include <stdio.h>

const float PI = 3.1415926;
const char ENVIRONMENT[] = "test";
const short int ENABLE_DEBUG = 1;

void testInteger()
{
	unsigned int unluckyNumber = 13;
	int daysInAYear = 365;
	short int byte = 0b1000;
	long int blue = 0x0000FF;

	printf("unluckyNumber = %d\n",unluckyNumber);
	printf("daysInAYear = %d\n",daysInAYear);
	printf("byte = %d\n",byte);
	printf("blue = %ld\n",blue);				
}

void testFloat()
{
	double pi = 3.1415926;
	float piecesOfAPizza = 8.0;
	float piecesOfACake = (float) 4;	
	printf("pi = %f.7\n",pi);
	printf("byte = %f.1\n",piecesOfAPizza);
	printf("blue = %f\n",piecesOfACake);
}
	
void testString()
{
	char letter = 'a';
	char word[] = "alphabet";
	char aguy[] = "john jonah jameson";
	
	printf("letter = %c\n",letter);
	printf("word = %s\n",word);
	printf("aguy = %s\n",aguy);
}
	
void testBoolean()
{
	short int theEarthIsRound = 1;
	short int theEarthIsFlat = 0;
	
	printf("theEarthIsRound = %d\n",theEarthIsRound);
	printf("theEarthIsFlat = %d\n",theEarthIsFlat);
}
	
void testConstant()
{
	printf("PI = %f.7\n",PI);
	printf("ENVIRONMENT = %s\n",ENVIRONMENT);
	printf("ENABLE_DEBUG = %d\n",ENABLE_DEBUG);				
}			

void main()
{
	testInteger();
	testString();
	testBoolean();
	testConstant();
}
