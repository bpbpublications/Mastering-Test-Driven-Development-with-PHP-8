<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

class FilesystemTest extends TestCase {
	/** @coversNothing **/
	#[CoversNothing()]	
	public function testCreateSaveAndReadFileLineByLine()
	{
		$path = __DIR__;
		$this->assertTrue(file_exists($path));
		$fileName = $path . DIRECTORY_SEPARATOR . 'filexample.txt';
		if (!file_exists($fileName)){
			$handle = fopen($fileName,'a');
			$this->assertEquals('resource',gettype($handle));
			fwrite($handle, str_pad("The mouse ate the king of Rome's clothes,\n",80,' '));
			fwrite($handle, str_pad("so the king of Rome said he had new clothes.",80,' '));
			fclose($handle);
		}
		$content = '';
		$handle = fopen($fileName,'r');
		while (!feof($handle)){
			$content .= fread($handle,80);
		}
		fclose($handle);
		$this->assertNotEquals('resource',gettype($handle));
		$this->assertStringContainsString('mouse',$content);
		$this->assertTrue(str_contains($content,'king'));
		$this->assertFalse(str_contains($content,'queen'));
		$this->assertStringContainsString('Rome',$content);
		$this->assertTrue(str_contains($content,'clothes'));
		$this->assertFalse(str_contains($content,'Romulus'));
		unlink($fileName);			
	}

	/** @coversNothing **/
	#[CoversNothing()]	
	public function testCreateSaveAndReadFileAtOnce()
	{
		$path = __DIR__;
		$this->assertTrue(file_exists($path));
		$fileName = $path . DIRECTORY_SEPARATOR . 'filexample.txt';
		$content = <<<TEXT
		The mouse ate the king of Rome's clothes,
		so the king of Rome said he had new clothes.
		TEXT;
		file_put_contents($fileName,$content);
		$content = '';
		$this->assertEmpty($content);
		$content = file_get_contents($fileName);
		$this->assertStringContainsString('mouse',$content);
		$this->assertTrue(str_contains($content,'king'));
		$this->assertFalse(str_contains($content,'queen'));
		$this->assertStringContainsString('Rome',$content);
		$this->assertTrue(str_contains($content,'clothes'));
		$this->assertFalse(str_contains($content,'Romulus'));
		unlink($fileName);			
	}
		
}
