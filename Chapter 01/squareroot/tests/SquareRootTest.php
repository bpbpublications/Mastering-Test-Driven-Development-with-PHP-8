<?php
use PHPUnit\Framework\TestCase;


/**
@covers \square_root
**/
class SquareRootTest extends TestCase {
	public function testExtraction()
	{
		$this->assertEquals(12,square_root(144));
	}
}
