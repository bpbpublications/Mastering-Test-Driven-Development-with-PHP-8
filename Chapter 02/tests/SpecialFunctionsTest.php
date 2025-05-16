<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

class SpecialFunctionsTest extends TestCase {
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testVariableFunctions()
	{	
		$operation = 'sin';
		$this->assertEquals(round(sqrt(2)/2,2), round($operation(deg2rad(45)),2));
		$operation = 'cos';	
		$this->assertEquals(round(sqrt(2)/2,2), round($operation(deg2rad(45)),2));
		$operation = 'tan';	
		$this->assertEquals(1, round($operation(deg2rad(45))));
	}
		
}
