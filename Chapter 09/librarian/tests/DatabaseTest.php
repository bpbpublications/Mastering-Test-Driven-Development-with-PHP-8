<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Librarian\Test\AbstractDatabaseTest;
use Librarian\Model\ORM\Table;
use Librarian\Model\ORM\AuthorTableGateway;
use Librarian\Model\ORM\BookTableGateway;
use Librarian\Model\ORM\AuthorFinder;
use Librarian\Model\ORM\BookFinder;

class DatabaseTest extends AbstractDatabaseTest
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthor()
    {
        $this->assertTrue((new AuthorTableGateway())->save('Wells','Herbert','George'));
        (new Table('authors'))->truncate();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthor()
    {
        (new AuthorTableGateway())->save('Von Goethe','Wolfgang','Johann');
        (new AuthorTableGateway())->save('Fitzgerald','Scott','Francis');
        (new AuthorTableGateway())->save('Doyle','Arthur','Conan');
        $author = (new AuthorFinder())->readByCode(2);
        $this->assertEquals('Scott',$author->middleName);
        (new Table('authors'))->truncate();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthors()
    {
        (new AuthorTableGateway())->save('Shelley','Wollstonecraft','Mary');
        (new AuthorTableGateway())->save('Christie','Mary','Agatha');
        (new AuthorTableGateway())->save('Lispector','Pinkhasivna','Chaya');
        $authors = (new AuthorFinder())->readAll();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors->get(1)->firstName);
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthor()
    {
        (new AuthorTableGateway())->save('Maupassant','de','Guy');
        (new AuthorTableGateway())->save('Saint-Exupéry','de','Antoine');
        (new AuthorTableGateway())->save('Balzac','de','Honoré');
        $author = (new AuthorFinder())->readByCode(1);
        $this->assertEquals('Guy',$author->firstName);
        (new AuthorTableGateway())->update(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = (new AuthorFinder())->readByCode(1);
        $this->assertEquals('Rudolf',$author->firstName);
        (new Table('authors'))->truncate();        
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthor()
    {
        (new AuthorTableGateway())->save('Assis','de','Machado');
        (new AuthorTableGateway())->save('Alencar','de','José');
        (new AuthorTableGateway())->save('Queiroz','de','Rachel');
        $author = (new AuthorFinder())->readByCode(2);
        $this->assertEquals('Alencar',$author->lastName);
        (new AuthorTableGateway())->delete(2);
        $author = (new AuthorFinder())->readByCode(2);
        $this->assertEmpty($author->code);
        (new Table('authors'))->truncate();
    }    
    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBook()
    {
        (new AuthorTableGateway())->save('Wells','Herbert','George');
        $this->assertTrue((new BookTableGateway())->save('The Time Machine',1));
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBook()
    {
        (new AuthorTableGateway())->save('Von Goethe','Wolfgang','Johann');
        (new BookTableGateway())->save('Fausto',1);
        $book = (new BookFinder())->readByCode(1);
        $this->assertStringContainsString('Fausto',$book->title);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooks()
    {
        (new AuthorTableGateway())->save('Christie','Mary','Agatha');
        (new BookTableGateway())->save('Murder on the Orient Express',1);
        (new BookTableGateway())->save('Death on the Nile',1);
        (new BookTableGateway())->save('Halloween Party',1); 
        $books = (new BookFinder())->readAll();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books->get(0)->title);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBook()
    {
        (new AuthorTableGateway())->save('Balzac','de','Honoré');
        (new BookTableGateway())->save('La Vendetta',1);
        (new BookTableGateway())->save('Le Contrat de mariage',1);
        (new BookTableGateway())->save('La Femme de trente ans',1);
        $book = (new BookFinder())->readByCode(2);
        $this->assertStringContainsString('mariage',$book->title);
        (new BookTableGateway())->update(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = (new BookFinder())->readByCode(2);
        $this->assertStringContainsString('vie',$book->title);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBook()
    {
        (new AuthorTableGateway())->save('Alencar','de','José');
        (new BookTableGateway())->save('O Guarani',1);
        (new BookTableGateway())->save('Iracema',1);
        (new BookTableGateway())->save('Ubirajara',1);
        $book = (new BookFinder())->readByCode(2);
        $this->assertStringContainsString('Iracema',$book->title);
        (new BookTableGateway())->delete(2);
        $book = (new BookFinder())->readByCode(2);
        $this->assertEmpty($book->code);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
}
