<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Fgsl\Rest\Rest;
use Librarian\Test\PHPServer;

class RouterTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        PHPServer::getInstance()->start();
    }

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testInvalidRoute()
    {
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/view/index/index.phtml',200);
        $this->assertStringContainsString('404 Not Found',$response);
    }

    public static function tearDownAfterClass(): void
    {
        PHPServer::getInstance()->stop();
    }
}
