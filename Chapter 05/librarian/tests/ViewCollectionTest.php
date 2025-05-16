<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\Attributes\CoversNothing;
use Fgsl\Rest\Rest;

require_once 'ViewDatabaseTest.php';

class ViewCollectionTest extends ViewDatabaseTest
{
    public static function setUpBeforeClass(): void
    {
        self::$process = self::startPHPServer();
        replaceConfigFileContent("'database' => 'librarian'","'database' => 'librarian_test'");
        replaceConfigFileContent("'storage_format' => 'txt'","'storage_format' => 'ddb'");
    }

    public static function tearDownAfterClass():void
    {
        proc_terminate(self::$process);
        replaceConfigFileContent("'database' => 'librarian_test'","'database' => 'librarian'");
        replaceConfigFileContent("'storage_format' => 'ddb'","'storage_format' => 'txt'");
    }
}
