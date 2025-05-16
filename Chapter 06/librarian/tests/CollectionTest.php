<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

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
    public function testSaveAuthorInCollection()
    {
        $this->assertTrue(saveAuthorInCollection('Wells','Herbert','George'));
        dropCollection('authors');
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorInCollection()
    {
        saveAuthorInCollection('Von Goethe','Wolfgang','Johann');
        saveAuthorInCollection('Fitzgerald','Scott','Francis');
        saveAuthorInCollection('Doyle','Arthur','Conan');
        $author = readAuthorInCollectionByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        dropCollection('authors');        
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorsInCollection()
    {
        saveAuthorInCollection('Shelley','Wollstonecraft','Mary');
        saveAuthorInCollection('Christie','Mary','Agatha');
        saveAuthorInCollection('Lispector','Pinkhasivna','Chaya');
        $authors = readAuthorsInCollection();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthorInCollection()
    {
        saveAuthorInCollection('Maupassant','de','Guy');
        saveAuthorInCollection('Saint-Exupéry','de','Antoine');
        saveAuthorInCollection('Balzac','de','Honoré');
        $author = readAuthorInCollectionByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        updateAuthorInCollection(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = readAuthorInCollectionByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);
        dropCollection('authors');        
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthorInCollection()
    {
        saveAuthorInCollection('Assis','de','Machado');
        saveAuthorInCollection('Alencar','de','José');
        saveAuthorInCollection('Queiroz','de','Rachel');
        $author = readAuthorInCollectionByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        deleteAuthorInCollection(2);
        $author = readAuthorInCollectionByCode(2);
        $this->assertEmpty($author);
        dropCollection('authors');
    }

    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBookInCollection()
    {
        saveAuthorInCollection('Wells','Herbert','George');
        $this->assertTrue(saveBookInCollection('The Time Machine',1));
        dropCollection('books');
        dropCollection('authors');
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBookInCollection()
    {
        saveAuthorInCollection('Von Goethe','Wolfgang','Johann');
        saveBookInCollection('Fausto',1);
        $book = readBookInCollectionByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        dropCollection('books');
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooksInCollection()
    {
        saveAuthorInCollection('Christie','Mary','Agatha');
        saveBookInCollection('Murder on the Orient Express',1);
        saveBookInCollection('Death on the Nile',1);
        saveBookInCollection('Halloween Party',1); 
        $books = readBooksInCollection();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        dropCollection('books');
        dropCollection('authors');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBookInCollection()
    {
        saveAuthorInCollection('Balzac','de','Honoré');
        saveBookInCollection('La Vendetta',1);
        saveBookInCollection('Le Contrat de mariage',1);
        saveBookInCollection('La Femme de trente ans',1);
        $book = readBookInCollectionByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        updateBookInCollection(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = readBookInCollectionByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        dropCollection('books');
        dropCollection('authors');
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBookInCollection()
    {
        saveAuthorInCollection('Alencar','de','José');
        saveBookInCollection('O Guarani',1);
        saveBookInCollection('Iracema',1);
        saveBookInCollection('Ubirajara',1);
        $book = readBookInCollectionByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        deleteBookInCollection(2);
        $book = readBookInCollectionByCode(2);
        $this->assertEmpty($book);
        dropCollection('books');
        dropCollection('authors');
    }    
    
    public static function tearDownAfterClass():void
    {
        replaceConfigFileContent("'database' => 'librarian_test'","'database' => 'librarian'");
    }    
}
