<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

define('PI',3.1415926);
define('ENVIRONMENT','test');
define('ENABLE_DEBUG',true);

enum Process {
	case Running;
	case Blocked;
	case Ready;
}

define('PROCESS_RUNNING',0);
define('PROCESS_BLOCKED',1);
define('PROCESS_READY',2);

class DataTypesTest extends TestCase {
	/** @coversNothing **/
	#[CoversNothing()]
	public function testInteger()
	{
		$unluckyNumber = 13;
		$daysInAYear = 365;
		$byte = 0b1000;
		$sixteen = 0o20;
		$blue = 0x0000FF;
		
		$this->assertEquals('integer',gettype($unluckyNumber));
		$this->assertTrue(gettype($daysInAYear) == 'integer');
		$this->assertEquals(8,$byte);
		$this->assertEquals(16,$sixteen);
		$this->assertEquals(255,$blue);
	}

	/** @coversNothing **/
	#[CoversNothing()]
	public function testFloat()
	{
		$pi = 3.1415926;
		$piecesOfAPizza = 8.0;
		$piecesOfACake = (float) 4;
		$avogadro = 6.02214076e23;
		$nano = 1E-9;

 		$this->assertEquals('double',gettype($pi));
		$this->assertEquals('double',gettype($piecesOfAPizza));
		$this->assertEquals('double',gettype($piecesOfACake));
		$this->assertEquals('double',gettype($avogadro));
		$this->assertEquals('double',gettype($nano));		
	}
	
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testString()
	{
		$letter = 'a';
		$word = 'alphabet';
		$aguy = 'john jonah jameson';
		$anotherguy = "john jonah jameson";
		$sentencewithsinglequotes = '$letter is the first letter of the $word';
		$sentencewithdoublequotes = "$letter is the first letter of the $word";

		$this->assertEquals('string',gettype($letter));
		$this->assertTrue(gettype($word) == 'string');
		$this->assertEquals($aguy,$anotherguy);
		$this->assertStringContainsString('word',$sentencewithsinglequotes);
		$this->assertStringContainsString('alphabet',$sentencewithdoublequotes);		 
	}
	
	/** @coversNothing **/	
	#[CoversNothing()]
	public function testBoolean()
	{
		$theEarthIsRound = true;
		$theEarthIsFlat = false;

		$this->assertTrue($theEarthIsRound);
		$this->assertNotTrue($theEarthIsFlat);
		$this->assertEquals(true,1);
		$this->assertEquals(true,2999);
		$this->assertEquals(true,'a');
		$this->assertEquals(true,'alphabet');
		$this->assertEquals(false,0);
		$this->assertEquals(false,'');		
	}
	
	/** @coversNothing **/	
	#[CoversNothing()]
	public function testConstant()
	{
		$this->assertEquals(3.1415926, PI);
		$this->assertEquals('test', ENVIRONMENT);
		$this->assertTrue(ENABLE_DEBUG);				
	}			

	/** @coversNothing **/	
	#[CoversNothing()]
	public function testEnumeration()
	{
		$state = Process::Running;
		$this->assertEquals(Process::Running, $state);
		$state = Process::Blocked;
		$this->assertTrue(Process::Blocked == $state);		
		$state = Process::Ready;
		$this->assertEquals(Process::Ready, $state);
	}

	/** @coversNothing **/	
	#[CoversNothing()]
	public function testNull()
	{
		$goal = null;
		$this->assertEquals('NULL',gettype($goal));
		$this->assertNull($goal);
		$this->assertTrue($goal == null);
		$this->assertTrue(is_null($goal));
		$this->assertFalse(is_null(0));
		$this->assertFalse(is_null(''));
		$this->assertFalse(is_null(false));
		$this->assertTrue(is_null($notDefined));
		$this->assertEquals(null,$goal);
		$this->assertEquals(null, 0);
		$this->assertEquals(null,'');
		$this->assertEquals(null,false);
		$this->assertEquals(null,$notDefined);		
		$this->assertTrue(empty($goal));
		$this->assertTrue(empty(0));
		$this->assertTrue(empty(''));
		$this->assertTrue(empty(false));
		$this->assertTrue(empty($notDefined));
		$this->assertFalse(isset($goal));
		$this->assertFalse(isset($notDefined));		
	}
	
	/** @coversNothing **/	
	#[CoversNothing()]
	public function testRange()
	{
		$maxInteger = PHP_INT_MAX;
		$integerOutOfLimit = 2 * PHP_INT_MAX;	
		$maxFloat = 1.8E308;
		$floatOutOfLimit = 2 * $maxFloat;
		$halfInfinite = $floatOutOfLimit / 2;
		$infiniteDivision = $floatOutOfLimit / $floatOutOfLimit;
		$halfOfANAN = $infiniteDivision / 2;
		$this->assertEquals('double', gettype($integerOutOfLimit));
		$this->assertEquals('double', gettype($floatOutOfLimit));
		$this->assertEquals('INF', $floatOutOfLimit);
		$this->assertEquals('INF',$halfInfinite);
		$this->assertEquals('NAN',$infiniteDivision);
		$this->assertEquals('NAN',$halfOfANAN);
	}
}
