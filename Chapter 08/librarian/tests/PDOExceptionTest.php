<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\Attributes\CoversNothing;
use Librarian\Test\AbstractDatabaseTest;
use Librarian\Model\PDO\AuthorPDO;
use Librarian\Model\PDO\BookPDO;
use Librarian\Model\ORM\Table;

class PDOExceptionTest extends AbstractDatabaseTest
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthor()
    {
        $authorPDO = new AuthorPDO();
        try {
            $authorPDO->save('Karl', 'Philipp', 'Hottentottenstottertrottelmutter');
        } catch (\Throwable $th) {
            $this->assertInstanceOf(\PDOException::class,$th);
        }
        (new Table('authors'))->truncate();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthor()
    {
        $authorPDO = new AuthorPDO();
        $author = $authorPDO->readByCode(2);
        $this->assertIsArray($author);
        $this->assertThat($author,$this->logicalNot($this->arrayHasKey('first_name')));
        (new Table('authors'))->truncate();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthors()
    {
        $authorPDO = new AuthorPDO();
        $authors = $authorPDO->readAuthors();
        $this->assertCount(0,$authors);
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthor()
    {
        $authorPDO = new AuthorPDO();
        $this->assertFalse($authorPDO->update(1,['first_name' =>'Rudolf','middle_name'=>'Erich','last_name' =>'Raspe']));
        (new Table('authors'))->truncate();
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthor()
    {
        $authorPDO = new AuthorPDO();
        $this->assertFalse($authorPDO->delete(2));
        (new Table('authors'))->truncate();
    }    
    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBook()
    {
        $bookPDO = new BookPDO();
        try {
            $bookPDO->save('The Time Machine',1);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(\PDOException::class, $th);
        }        
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBook()
    {
        $bookPDO = new BookPDO();        
        $book = $bookPDO->readByCode(1);
        $this->assertIsArray($book);
        $this->assertThat($book,$this->logicalNot($this->arrayHasKey('title')));
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooks()
    {
        $bookPDO = new BookPDO();        
        $books = $bookPDO->readBooks();
        $this->assertCount(0,$books);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBook()
    {
        $bookPDO = new BookPDO();        
        $this->assertFalse($bookPDO->update(2,[
            'title' => 'Un début dans la vie'
        ]));
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBook()
    {
        $bookPDO = new BookPDO();        
        $this->assertFalse($bookPDO->delete(2));
        $book = $bookPDO->readByCode(2);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
}
