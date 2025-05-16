<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
namespace Librarian\Test;
use PHPUnit\Framework\TestCase;

abstract class AbstractDatabaseTest extends TestCase
{
    // author tests
    abstract  public function testSaveAuthor();

    abstract  public function testReadAuthor();

    abstract  public function testReadAuthors();
    
    abstract  public function testUpdateAuthor();
    
    abstract  public function testDeleteAuthor();
    // book tests
  
    abstract public function testReadBook();
    
    abstract public function testReadBooks();
    
    abstract public function testUpdateBook();
   
    abstract public function testDeleteBook();
}
