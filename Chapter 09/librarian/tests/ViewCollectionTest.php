<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\Attributes\CoversNothing;
use Fgsl\Rest\Rest;
use Librarian\Test\PHPServer;

require_once 'ViewDatabaseTest.php';

class ViewCollectionTest extends ViewDatabaseTest
{
    public static function setUpBeforeClass(): void
    {
        putenv('LIBRARIAN_TEST_ENVIRONMENT=true');        
        PHPServer::getInstance()->start();
        replaceConfigFileContent("'storage_format' => 'txt'","'storage_format' => 'ddb'");
        clearEntity('author');
        clearEntity('book');        
    }

    public static function tearDownAfterClass():void
    {
        PHPServer::getInstance()->stop();
        replaceConfigFileContent("'storage_format' => 'ddb'","'storage_format' => 'txt'");
        putenv('LIBRARIAN_TEST_ENVIRONMENT=false');
    }
}
