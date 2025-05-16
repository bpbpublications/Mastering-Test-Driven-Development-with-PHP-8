<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Librarian\Model\ODM\AuthorCollection;
use Librarian\Model\ODM\BookCollection;
use Librarian\Model\ODM\AuthorCollectionFinder;
use Librarian\Model\ODM\BookCollectionFinder;

class CollectionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        replaceConfigFileContent("'database' => 'librarian'","'database' => 'librarian_test'");
    }

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthor()
    {
        $this->assertTrue((new AuthorCollection())->save('Wells','Herbert','George'));
        dropCollection('authors');
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthor()
    {
        $authorCollection = new AuthorCollection();
        $authorCollection->save('Von Goethe','Wolfgang','Johann');
        $authorCollection->save('Fitzgerald','Scott','Francis');
        $authorCollection->save('Doyle','Arthur','Conan');
        $author = (new AuthorCollectionFinder())->readByCode(2);
        $this->assertEquals('Scott',$author->middleName);
        dropCollection('authors');
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthors()
    {
        $authorCollection = new AuthorCollection();        
        $authorCollection->save('Shelley','Wollstonecraft','Mary');
        $authorCollection->save('Christie','Mary','Agatha');
        $authorCollection->save('Lispector','Pinkhasivna','Chaya');
        $authors = (new AuthorCollectionFinder())->readAll();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors->get(1)->firstName);
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthor()
    {
        $authorCollection = new AuthorCollection();
        $authorCollection->save('Maupassant','de','Guy');
        $authorCollection->save('Saint-Exupéry','de','Antoine');
        $authorCollection->save('Balzac','de','Honoré');
        $authorCollectionFinder = new AuthorCollectionFinder();
        $author = $authorCollectionFinder->readByCode(1);
        $this->assertEquals('Guy',$author->firstName);
        $authorCollection->update(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = $authorCollectionFinder->readByCode(1);
        $this->assertEquals('Rudolf',$author->firstName);
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthor()
    {
        $authorCollection = new AuthorCollection();
        $authorCollection->save('Assis','de','Machado');
        $authorCollection->save('Alencar','de','José');
        $authorCollection->save('Queiroz','de','Rachel');
        $authorCollectionFinder = new AuthorCollectionFinder();
        $author = $authorCollectionFinder->readByCode(2);
        $this->assertEquals('Alencar',$author->lastName);
        $authorCollection->delete(2);
        $author = $authorCollectionFinder->readByCode(2);
        $this->assertEmpty($author->code);
        dropCollection('authors');
    }

    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBook()
    {
        (new AuthorCollection())->save('Wells','Herbert','George');
        $this->assertTrue((new BookCollection())->save('The Time Machine',1));
        dropCollection('books');
        dropCollection('authors');
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBook()
    {
        (new AuthorCollection())->save('Von Goethe','Wolfgang','Johann');
        (new BookCollection())->save('Fausto',1);
        $book = (new BookCollectionFinder())->readByCode(1);
        $this->assertStringContainsString('Fausto',$book->title);
        dropCollection('books');
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooks()
    {
        (new AuthorCollection())->save('Christie','Mary','Agatha');
        $bookCollection = new BookCollection();
        $bookCollection->save('Murder on the Orient Express',1);
        $bookCollection->save('Death on the Nile',1);
        $bookCollection->save('Halloween Party',1); 
        $books = (new BookCollectionFinder())->readAll();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books->get(0)->title);
        dropCollection('books');
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBook()
    {
        (new AuthorCollection())->save('Balzac','de','Honoré');
        $bookCollection = new BookCollection();
        $bookCollection->save('La Vendetta',1);
        $bookCollection->save('Le Contrat de mariage',1);
        $bookCollection->save('La Femme de trente ans',1);
        $bookCollectionFinder = new BookCollectionFinder();
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertStringContainsString('mariage',$book->title);
        $bookCollection->update(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertStringContainsString('vie',$book->title);
        dropCollection('books');
        dropCollection('authors');
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBook()
    {
        (new AuthorCollection())->save('Alencar','de','José');
        $bookCollection = new BookCollection();
        $bookCollection->save('O Guarani',1);
        $bookCollection->save('Iracema',1);
        $bookCollection->save('Ubirajara',1);
        $bookCollectionFinder = new BookCollectionFinder();
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertStringContainsString('Iracema',$book->title);
        $bookCollection->delete(2);
        $book = $bookCollectionFinder->readByCode(2);
        $this->assertEmpty($book->code);
        dropCollection('books');
        dropCollection('authors');
    }    
    
    public static function tearDownAfterClass():void
    {
        replaceConfigFileContent("'database' => 'librarian_test'","'database' => 'librarian'");
    }    
}
