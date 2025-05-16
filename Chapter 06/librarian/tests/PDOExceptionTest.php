<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\Attributes\CoversNothing;
use Librarian\Test\AbstractDatabaseTest;
use Librarian\Model\PDO\AuthorPDO;
use Librarian\Model\PDO\BookPDO;

class PDOExceptionTest extends AbstractDatabaseTest
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthorInDatabase()
    {
        $authorPDO = new AuthorPDO();
        try {
            $authorPDO->save('Karl', 'Philipp', 'Hottentottenstottertrottelmutter');
        } catch (\Throwable $th) {
            $this->assertInstanceOf(\PDOException::class,$th);
        }
        truncateTable('authors');
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorInDatabase()
    {
        $authorPDO = new AuthorPDO();
        $author = $authorPDO->readByCode(2);
        $this->assertIsArray($author);
        $this->assertThat($author,$this->logicalNot($this->arrayHasKey('first_name')));
        truncateTable('authors');
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorsInDatabase()
    {
        $authorPDO = new AuthorPDO();
        $authors = $authorPDO->readAuthors();
        $this->assertCount(0,$authors);
        truncateTable('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthorInDatabase()
    {
        $authorPDO = new AuthorPDO();
        $this->assertFalse($authorPDO->update(1,['first_name' =>'Rudolf','middle_name'=>'Erich','last_name' =>'Raspe']));
        truncateTable('authors');
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthorInDatabase()
    {
        $authorPDO = new AuthorPDO();
        $this->assertFalse($authorPDO->delete(2));
        truncateTable('authors');
    }    
    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBookInDatabase()
    {
        $bookPDO = new BookPDO();
        try {
            $bookPDO->save('The Time Machine',1);
        } catch (\Throwable $th) {
            $this->assertInstanceOf(\PDOException::class, $th);
        }        
        truncateTable('books');
        truncateTable('authors');
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBookInDatabase()
    {
        $bookPDO = new BookPDO();        
        $book = $bookPDO->readByCode(1);
        $this->assertIsArray($book);
        $this->assertThat($book,$this->logicalNot($this->arrayHasKey('title')));
        truncateTable('books');
        truncateTable('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooksInDatabase()
    {
        $bookPDO = new BookPDO();        
        $books = $bookPDO->readBooks();
        $this->assertCount(0,$books);
        truncateTable('books');
        truncateTable('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBookInDatabase()
    {
        $bookPDO = new BookPDO();        
        $this->assertFalse($bookPDO->update(2,[
            'title' => 'Un début dans la vie'
        ]));
        truncateTable('books');
        truncateTable('authors');
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBookInDatabase()
    {
        $bookPDO = new BookPDO();        
        $this->assertFalse($bookPDO->delete(2));
        $book = $bookPDO->readByCode(2);
        truncateTable('books');
        truncateTable('authors');
    }
}
