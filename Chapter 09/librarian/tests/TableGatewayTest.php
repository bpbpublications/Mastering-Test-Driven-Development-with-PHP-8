<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Librarian\Model\ORM\AuthorTableGateway;
use Librarian\Model\ORM\BookTableGateway;
use Librarian\Model\ORM\Table;

class TableGatewayTest extends TestCase
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthor()
    {
        $authorTableGateway = new AuthorTableGateway();
        $this->assertTrue($authorTableGateway->save('Wells', 'Herbert', 'George'));
        (new Table('authors'))->truncate();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthor()
    {
        $authorTableGateway = new AuthorTableGateway();
        $authorTableGateway->save('Von Goethe', 'Wolfgang', 'Johann',);
        $authorTableGateway->save('Fitzgerald', 'Scott', 'Francis');
        $authorTableGateway->save('Doyle', 'Conan', 'Arthur');
        $author = $authorTableGateway->readByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        (new Table('authors'))->truncate();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthors()
    {
        $authorTableGateway = new AuthorTableGateway();
        $authorTableGateway->save('Shelley', 'Wollstonecraft', 'Mary');
        $authorTableGateway->save('Christie', 'Mary', 'Agatha');
        $authorTableGateway->save('Lispector', 'Pinkhasivna', 'Chaya');
        $authors = $authorTableGateway->readAuthors();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthor()
    {
        $authorTableGateway = new AuthorTableGateway();
        $authorTableGateway->save('Maupassant', 'de', 'Guy');
        $authorTableGateway->save('Saint-Exupéry', 'de', 'Antoine');
        $authorTableGateway->save('Balzac', 'de', 'Honoré');
        $author = $authorTableGateway->readByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        $authorTableGateway->update(1,['first_name' =>'Rudolf','middle_name'=>'Erich','last_name' =>'Raspe']);
        $author = $authorTableGateway->readByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);
        (new Table('authors'))->truncate();
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthor()
    {
        $authorTableGateway = new AuthorTableGateway();
        $authorTableGateway->save('Assis', 'de', 'Machado');
        $authorTableGateway->save('Alencar', 'de', 'José');
        $authorTableGateway->save('Queiroz', 'de', 'Rachel');
        $author = $authorTableGateway->readByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        $authorTableGateway->delete(2);
        $author = $authorTableGateway->readByCode(2);
        $this->assertEmpty($author);
        (new Table('authors'))->truncate();
    }    
    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBook()
    {
        $authorTableGateway = new AuthorTableGateway();
        $bookTableGateway = new BookTableGateway();
        $authorTableGateway->save('Wells', 'George', 'Herbert');
        $this->assertTrue($bookTableGateway->save('The Time Machine',1));
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBook()
    {
        $authorTableGateway = new AuthorTableGateway();
        $bookTableGateway = new BookTableGateway();        
        $authorTableGateway->save('Von Goethe', 'Wolfgang', 'Johann');
        $bookTableGateway->save('Fausto',1);
        $book = $bookTableGateway->readByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooks()
    {
        $authorTableGateway = new AuthorTableGateway();
        $bookTableGateway = new BookTableGateway();        
        $authorTableGateway->save('Christie', 'Mary', 'Agatha');
        $bookTableGateway->save('Murder on the Orient Express',1);
        $bookTableGateway->save('Death on the Nile',1);
        $bookTableGateway->save('Halloween Party',1); 
        $books = $bookTableGateway->readBooks();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBook()
    {
        $authorTableGateway = new AuthorTableGateway();
        $bookTableGateway = new BookTableGateway();        
        $authorTableGateway->save('Balzac', 'de', 'Honoré');
        $bookTableGateway->save('La Vendetta',1);
        $bookTableGateway->save('Le Contrat de mariage',1);
        $bookTableGateway->save('La Femme de trente ans',1);
        $book = $bookTableGateway->readByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        $bookTableGateway->update(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = $bookTableGateway->readByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBook()
    {
        $authorTableGateway = new AuthorTableGateway();
        $bookTableGateway = new BookTableGateway();        
        $authorTableGateway->save('Alencar', 'de', 'José');
        $bookTableGateway->save('O Guarani',1);
        $bookTableGateway->save('Iracema',1);
        $bookTableGateway->save('Ubirajara',1);
        $book = $bookTableGateway->readByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        $bookTableGateway->delete(2);
        $book = $bookTableGateway->readByCode(2);
        $this->assertEmpty($book);
        (new Table('books'))->truncate();
        (new Table('authors'))->truncate();
    }
}
