<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use Librarian\Test\AbstractBackupTest;
use PHPUnit\Framework\Attributes\CoversNothing;

class JSONTest extends AbstractBackupTest
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthorInJSON()
    {
        $this->assertTrue(saveAuthorInJSON('Wells','Herbert','George'));
        $filepath = getConfig()['author_json_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorInJSON()
    {
        saveAuthorInJSON('Von Goethe','Wolfgang','Johann');
        saveAuthorInJSON('Fitzgerald','Scott','Francis');
        saveAuthorInJSON('Doyle','Arthur','Conan');
        $author = readAuthorInJSONByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        $filepath = getConfig()['author_json_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorsInJSON()
    {
        saveAuthorInJSON('Shelley','Wollstonecraft','Mary');
        saveAuthorInJSON('Christie','Mary','Agatha');
        saveAuthorInJSON('Lispector','Pinkhasivna','Chaya');
        $authors = readAuthorsInJSON();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        $filepath = getConfig()['author_json_filepath'];
        unlink($filepath);
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthorInJSON()
    {
        saveAuthorInJSON('Maupassant','de','Guy');
        saveAuthorInJSON('Saint-Exupéry','de','Antoine');
        saveAuthorInJSON('Balzac','de','Honoré');
        $author = readAuthorInJSONByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        updateAuthorInJSON(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = readAuthorInJSONByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);
        $filepath = getConfig()['author_json_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthorInJSON()
    {
        saveAuthorInJSON('Assis','de','Machado');
        saveAuthorInJSON('Alencar','de','José');
        saveAuthorInJSON('Queiroz','de','Rachel');
        $author = readAuthorInJSONByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        deleteAuthorInJSON(2);
        $author = readAuthorInJSONByCode(2);
        $this->assertEmpty($author);
        $filepath = getConfig()['author_json_filepath'];
        unlink($filepath);
    }
    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBookInJSON()
    {
        saveAuthorInJSON('Wells','Herbert','George');
        $this->assertTrue(saveBookInJSON('The Time Machine',1));
        $this->deleteBooksAndAuthors('json');
    }
  
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBookInJSON()
    {
        saveAuthorInJSON('Von Goethe','Wolfgang','Johann');
        saveBookInJSON('Fausto',1);
        $book = readBookInJSONByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        $this->deleteBooksAndAuthors('json');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooksInJSON()
    {
        saveAuthorInJSON('Christie','Mary','Agatha');
        saveBookInJSON('Murder on the Orient Express',1);
        saveBookInJSON('Death on the Nile',1);
        saveBookInJSON('Halloween Party',1); 
        $books = readBooksInJSON();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        $this->deleteBooksAndAuthors('json');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBookInJSON()
    {
        saveAuthorInJSON('Balzac','de','Honoré');
        saveBookInJSON('La Vendetta',1);
        saveBookInJSON('Le Contrat de mariage',1);
        saveBookInJSON('La Femme de trente ans',1);
        $book = readBookInJSONByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        updateBookInJSON(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = readBookInJSONByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        $this->deleteBooksAndAuthors('json');        
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBookInJSON()
    {
        saveAuthorInJSON('Alencar','de','José');
        saveBookInJSON('O Guarani',1);
        saveBookInJSON('Iracema',1);
        saveBookInJSON('Ubirajara',1);
        $book = readBookInJSONByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        deleteBookInJSON(2);
        $book = readBookInJSONByCode(2);
        $this->assertEmpty($book);
        $this->deleteBooksAndAuthors('json');
    }
}
