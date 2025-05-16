<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
namespace Librarian\Test;
use PHPUnit\Framework\TestCase;

abstract class AbstractDatabaseTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        replaceConfigFileContent("'database' => 'librarian'","'database' => 'librarian_test'");
        replaceConfigFileContent('dbname=librarian','dbname=librarian_test');
    }
    // author tests
    abstract  public function testSaveAuthorInDatabase();

    abstract  public function testReadAuthorInDatabase();

    abstract  public function testReadAuthorsInDatabase();
    
    abstract  public function testUpdateAuthorInDatabase();
    
    abstract  public function testDeleteAuthorInDatabase();
    // book tests
  
    abstract public function testReadBookInDatabase();
    
    abstract public function testReadBooksInDatabase();
    
    abstract public function testUpdateBookInDatabase();
   
    abstract public function testDeleteBookInDatabase();

    public static function tearDownAfterClass():void
    {
        replaceConfigFileContent("'database' => 'librarian_test'","'database' => 'librarian'");
        replaceConfigFileContent('dbname=librarian_test','dbname=librarian');
    }    
}
