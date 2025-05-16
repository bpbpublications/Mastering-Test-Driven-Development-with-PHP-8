<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

class ConstantTest extends TestCase {
    /**
     * @coversNothing
     */
    #[CoversNothing()]    
    public function testConstant()
    {
        $this->assertTrue(LIBRARIAN_TEST_ENVIRONMENT);
    }
}