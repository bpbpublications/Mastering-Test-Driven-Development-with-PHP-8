<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Librarian\Model\ODM\AuthorCollectionRowGateway;
use Librarian\Model\ODM\BookCollectionRowGateway;
use Librarian\Model\ODM\AuthorCollectionFinder;
use Librarian\Model\ODM\BookCollectionFinder;
use Librarian\Model\ODM\Collection;
use Librarian\Model\Author;

require_once 'CollectionTest.php';

class CollectionRowGatewayTest extends CollectionTest
{
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthor()
    {
        $this->assertTrue((new AuthorCollectionRowGateway(0,'George','Herbert','Wells'))->save());
        (new Collection('authors'))->drop();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthor()
    {
        (new AuthorCollectionRowGateway(0,'Johann','Wolfgang','Von Goethe'))->save();
        (new AuthorCollectionRowGateway(0,'Francis','Scott','Fitzgerald'))->save();
        (new AuthorCollectionRowGateway(0,'Arthur','Conan','Doyle'))->save();
        $author = (new AuthorCollectionFinder())->readByCode(2);
        $this->assertEquals('Scott',$author->middleName);
        (new Collection('authors'))->drop();
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthors()
    {
        (new AuthorCollectionRowGateway(0,'Mary','Wollstonecraft','Shelley'))->save();
        (new AuthorCollectionRowGateway(0,'Agatha','Mary','Christie'))->save();
        (new AuthorCollectionRowGateway(0,'Chaya','Pinkhasivna','Lispector'))->save();
        $authors = (new AuthorCollectionFinder())->readAll();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors->get(1)->firstName);
        (new Collection('authors'))->drop();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthor()
    {
        (new AuthorCollectionRowGateway(0,'Guy','de','Maupassant'))->save();
        (new AuthorCollectionRowGateway(0,'Antoine','de','Saint-Exupéry'))->save();
        (new AuthorCollectionRowGateway(0,'Honoré','de','Balzac'))->save();
        $authorCollectionFinder = new AuthorCollectionFinder();
        $author = $authorCollectionFinder->readByCode(1);
        $this->assertEquals('Guy',$author->firstName);
        (new AuthorCollectionRowGateway(1,'Rudolf','Erich','Raspe'))->update();
        $author = $authorCollectionFinder->readByCode(1);
        $this->assertEquals('Rudolf',$author->firstName);
        (new Collection('authors'))->drop();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthor()
    {
        (new AuthorCollectionRowGateway(0,'Machado','de','Assis'))->save();
        (new AuthorCollectionRowGateway(0,'José','de','Alencar'))->save();
        (new AuthorCollectionRowGateway(0,'Rachel','de','Queiroz'))->save();
        $authorCollectionFinder = new AuthorCollectionFinder();
        $author = $authorCollectionFinder->readByCode(2);
        $this->assertEquals('Alencar',$author->lastName);
        (new AuthorCollectionRowGateway(2))->delete();
        $author = $authorCollectionFinder->readByCode(2);
        $this->assertEmpty($author->code);
        (new Collection('authors'))->drop();
    }

    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBook()
    {
        (new AuthorCollectionRowGateway(0,'George','Herbert','Wells'))->save();
        $this->assertTrue((new BookCollectionRowGateway(0,'The Time Machine',new Author(1)))->save());
        (new Collection('books'))->drop();
        (new Collection('authors'))->drop();
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBook()
    {
        (new AuthorCollectionRowGateway(0,'Johann','Wolfgang','Von Goethe'))->save();
        (new BookCollectionRowGateway(0,'Fausto',new Author(1)))->save();
        $book = (new BookCollectionFinder())->readByCode(1);
        $this->assertStringContainsString('Fausto',$book->title);
        (new Collection('books'))->drop();
        (new Collection('authors'))->drop();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooks()
    {
        (new AuthorCollectionRowGateway(0,'Agatha','Mary','Christie'))->save();
        (new BookCollectionRowGateway(0,'Murder on the Orient Express',new Author(1)))->save();
        (new BookCollectionRowGateway(0,'Death on the Nile',new Author(1)))->save();
        (new BookCollectionRowGateway(0,'Halloween Party',new Author(1)))->save(); 
        $books = (new BookCollectionFinder())->readAll();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books->get(0)->title);
        (new Collection('books'))->drop();
        (new Collection('authors'))->drop();
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBook()
    {
        (new AuthorCollectionRowGateway(0,'Honoré','de','Balzac'))->save();
        (new BookCollectionRowGateway(0,'La Vendetta',new Author(1)))->save();
        (new BookCollectionRowGateway(0,'Le Contrat de mariage',new Author(1)))->save();
        (new BookCollectionRowGateway(0,'La Femme de trente ans',new Author(1)))->save();
        $bookCollectionFinder = new BookCollectionFinder();
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertStringContainsString('mariage',$book->title);
        (new BookCollectionRowGateway(2,'Un début dans la vie',new Author(1)))->update();
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertStringContainsString('vie',$book->title);
        (new Collection('books'))->drop();
        (new Collection('authors'))->drop();
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBook()
    {
        (new AuthorCollectionRowGateway(0,'José','de','Alencar'))->save();
        (new BookCollectionRowGateway(0,'O Guarani',new Author(1)))->save();
        (new BookCollectionRowGateway(0,'Iracema',new Author(1)))->save();
        (new BookCollectionRowGateway(0,'Ubirajara',new Author(1)))->save();
        $bookCollectionFinder = new BookCollectionFinder();
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertStringContainsString('Iracema',$book->title);
        (new BookCollectionRowGateway(2))->delete();
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertEmpty($book->code);
        (new Collection('books'))->drop();
        (new Collection('authors'))->drop();
    }    
}
