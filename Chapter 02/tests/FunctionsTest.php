<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

function triple($number)
{
	return 3 * $number;
}

function getOddInTheWord($word)
{
	$length = strlen($word);
	for ($i=0;$i<$length;$i++){
		$letter = (int) $word[$i];	
		if ($letter % 2 != 0){
			return $word[$i];
		}
	} 
	return false;
}

class FunctionsTest extends TestCase {
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testReturn()
	{
		$this->assertEquals(6, triple(2));
		$this->assertEquals(7, getOddInTheWord('Ultra7'));
		$this->assertFalse(getOddInTheWord('No number here'));								
	}
		
}
