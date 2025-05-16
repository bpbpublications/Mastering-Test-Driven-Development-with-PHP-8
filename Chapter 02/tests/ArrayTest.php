<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

class ArrayTest extends TestCase {
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testCreateList()
	{
		$list = ['apple','banana','cranberries'];
		$this->assertIsArray($list);
		$this->assertEquals('banana',$list[1]);
		$list = [];
		$list[] = 'apple';
		$list[] = 'banana';
		$list[] = 'cranberries';
		$this->assertEquals('banana',$list[1]);			
	}

	/** @coversNothing **/
	#[CoversNothing()]	
	public function testCreateDictionary()
	{
		$aliens = [
			'Clark Kent' 	=> 'Krypton',
			'Jonn Jonzz' 	=> 'Mars',
			'Koriander'	=> 'Tamaran',
			'Lar Gand'	=> 'Daxam'
		];
		$this->assertIsArray($aliens);
		$this->assertArrayHasKey('Koriander',$aliens);			 		
	}
	
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testListForeach()
	{
		$pigs = ['Fifer','Fiddler','Practical'];
		$this->assertCount(3,$pigs);
		foreach($pigs as $pig){
			$this->assertIsString($pig);
		}
	}
	
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testDictionaryForeach()
	{
		$heroes = [
			'Bruce' => 'Batman',
			'Clark' => 'Superman',
			'Diana' => 'Wonder Woman'
		];
		foreach($heroes as $realName => $heroName){
			$this->assertTrue(array_key_exists($realName,$heroes));
			$this->assertTrue(in_array($heroName,$heroes));
		}
	}
	
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testCallbackFunction()
	{
		$callback = function(&$value,$key) {
			 $value = 'Black ' . $value;
		};
		$characters = ['Adam', 'Canary', 'Vulcan'];
		array_walk($characters, $callback);		
		$this->assertEquals('Black Adam', $characters[0]);		
		$this->assertEquals('Black Canary', $characters[1]);
		$this->assertEquals('Black Vulcan', $characters[2]);
	}
	
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testNoCallbackFunction()
	{
		$characters = ['Adam', 'Canary', 'Vulcan'];
		foreach($characters as $key => $value){
			$characters[$key] = 'Black ' . $value;
		}
		$this->assertEquals('Black Adam', $characters[0]);		
		$this->assertEquals('Black Canary', $characters[1]);
		$this->assertEquals('Black Vulcan', $characters[2]);
	}	
}
