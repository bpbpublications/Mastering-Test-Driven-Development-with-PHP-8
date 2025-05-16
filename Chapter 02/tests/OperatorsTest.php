<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

class OperatorsTest extends TestCase {
	/** @coversNothing **/
	#[CoversNothing()]		
	public function testArithmetic()
	{
		$number1 = 8;
		$number2 = 3;
		
		$this->assertEquals(11, $number1 + $number2);
		$this->assertEquals(5, $number1 - $number2);
		$this->assertEquals(-5, $number2 - $number1);
		$this->assertEquals(24, $number1 * $number2);
		$this->assertEquals(2.67, round($number1 / $number2,2));
		$this->assertEquals(2, $number1 % $number2);								
	}
	
	/** @coversNothing **/
	#[CoversNothing()]		
	public function testTypeConversion()
	{
		$number1 = 1;
		$number2 = "1";
		
		$this->assertEquals(2, $number1 + $number2);
		$this->assertEquals(0, $number1 - $number2);
		$this->assertEquals(1, $number1 * $number2);
		$this->assertEquals(1, $number1 / $number2);								
	}
	
	/** @coversNothing **/
	#[CoversNothing()]		
	public function testIncrementDecrement()
	{
		$number = 1;
		
		$this->assertEquals(2, ++$number);
		$this->assertEquals(1, --$number);
		$number+=2;
		$this->assertEquals(3, $number);
		$number-=3;
		$this->assertEquals(0, $number);								
	}	
	
	/** @coversNothing **/
	#[CoversNothing()]		
	public function testDivisionByZero()
	{
		try {		
			$impossibleValue = 2/0;
		} catch (DivisionByZeroError $e) {
			$this->assertStringContainsString('Division by zero',$e->getMessage());
		}		
	}
	
	/** @coversNothing **/
	#[CoversNothing()]		
	public function testLogic()
	{
		$sentence1 = true;
		$sentence2 = false;
		$sentence3 = true;
		$sentence4 = false;
		
		$this->assertTrue($sentence1);
		$this->assertFalse($sentence2);
		$this->assertTrue(!$sentence2);
		$this->assertFalse(!$sentence1);
		$this->assertTrue($sentence1 && $sentence3);
		$this->assertFalse($sentence1 && $sentence2);
		$this->assertFalse($sentence2 && $sentence4);
		$this->assertTrue($sentence1 || $sentence2);
		$this->assertTrue($sentence1 || $sentence3);
		$this->assertFalse($sentence2 || $sentence4);
		$this->assertTrue($sentence1 xor $sentence2);
		$this->assertFalse($sentence1 xor $sentence3);
		$this->assertFalse($sentence2 xor $sentence4);		
	}
	
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testComparison()
	{
		$nothing = 0;
		$one = 1;
		$oneDotZero = 1.0;
		$neon = 10;
		$starTrekIsCool = true;
		$lifeIsEasy = false;
		
		$this->assertTrue($one == $oneDotZero);
		$this->assertFalse($one != $oneDotZero);		
		$this->assertTrue($one != $neon);
		$this->assertTrue($one < $neon);
		$this->assertTrue($one >= $oneDotZero);
		$this->assertTrue($oneDotZero <= $neon);
		$this->assertTrue($neon > $one);		
		$this->assertTrue($one == $starTrekIsCool);
		$this->assertFalse($one === $starTrekIsCool);
		$this->assertTrue($one !== $oneDotZero);
		$this->assertTrue($nothing == $lifeIsEasy);
		$this->assertFalse($nothing === $lifeIsEasy);
		$this->assertTrue($nothing !== $lifeIsEasy);														
	}		
}
