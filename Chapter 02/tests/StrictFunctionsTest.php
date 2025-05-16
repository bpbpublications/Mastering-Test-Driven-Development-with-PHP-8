<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

function strictTriple(int $number):int
{
	return 3 * $number;
}

function getStrictOddInTheWord(string $word):string
{
	$length = strlen($word);
	for ($i=0;$i<$length;$i++){
		$letter = (int) $word[$i];
		if ($letter % 2 != 0){
			return $word[$i];
		}
	} 
	return '';
}

class StrictFunctionsTest extends TestCase {
	/** @coversNothing **/	
	#[CoversNothing()]	
	public function testReturn()
	{
		$this->assertEquals(6, strictTriple(2));
		$this->assertEquals(7, getStrictOddInTheWord('Ultra7'));
		$this->assertTrue(empty(getStrictOddInTheWord('No number here')));								
	}
		
}
