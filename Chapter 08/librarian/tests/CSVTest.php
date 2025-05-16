<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use Librarian\Test\AbstractBackupTest;
use PHPUnit\Framework\Attributes\CoversNothing;

class CSVTest extends AbstractBackupTest
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthorInCSV()
    {
        $this->assertTrue(saveAuthorInCSV('Wells','Herbert','George'));
        $filepath = getConfig()['author_csv_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorInCSV()
    {
        saveAuthorInCSV('Von Goethe','Wolfgang','Johann');
        saveAuthorInCSV('Fitzgerald','Scott','Francis');
        saveAuthorInCSV('Doyle','Arthur','Conan');
        $author = readAuthorInCSVByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        $filepath = getConfig()['author_csv_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorsInCSV()
    {
        saveAuthorInCSV('Shelley','Wollstonecraft','Mary');
        saveAuthorInCSV('Christie','Mary','Agatha');
        saveAuthorInCSV('Lispector','Pinkhasivna','Chaya');
        $authors = readAuthorsInCSV();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        $filepath = getConfig()['author_csv_filepath'];
        unlink($filepath);
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthorInCSV()
    {
        saveAuthorInCSV('Maupassant','de','Guy');
        saveAuthorInCSV('Saint-Exupéry','de','Antoine');
        saveAuthorInCSV('Balzac','de','Honoré');
        $author = readAuthorInCSVByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        updateAuthorInCSV(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = readAuthorInCSVByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);        
        $filepath = getConfig()['author_csv_filepath'];
        unlink($filepath);
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthorInCSV()
    {
        saveAuthorInCSV('Assis','de','Machado');
        saveAuthorInCSV('Alencar','de','José');
        saveAuthorInCSV('Queiroz','de','Rachel');
        $author = readAuthorInCSVByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        deleteAuthorInCSV(2);
        $author = readAuthorInCSVByCode(2);
        $this->assertEmpty($author);        
        $filepath = getConfig()['author_csv_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBookInCSV()
    {
        saveAuthorInCSV('Wells','Herbert','George');
        $this->assertTrue(saveBookInCSV('The Time Machine',1));
        $this->deleteBooksAndAuthors('csv');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBookInCSV()
    {
        saveAuthorInCSV('Von Goethe','Wolfgang','Johann');
        saveBookInCSV('Fausto',1);
        $book = readBookInCSVByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        $this->deleteBooksAndAuthors('csv');
    }
   
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooksInCSV()
    {
        saveAuthorInCSV('Christie','Mary','Agatha');
        saveBookInCSV('Murder on the Orient Express',1);
        saveBookInCSV('Death on the Nile',1);
        saveBookInCSV('Halloween Party',1);
        $books = readBooksInCSV();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        $this->deleteBooksAndAuthors('csv');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBookInCSV()
    {
        saveAuthorInCSV('Balzac','de','Honoré');
        saveBookInCSV('La Vendetta',1);
        saveBookInCSV('Le Contrat de mariage',1);
        saveBookInCSV('La Femme de trente ans',1);
        $book = readBookInCSVByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        updateBookInCSV(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = readBookInCSVByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        $this->deleteBooksAndAuthors('csv');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBookInCSV()
    {
        saveAuthorInCSV('Alencar','de','José');
        saveBookInCSV('O Guarani',1);
        saveBookInCSV('Iracema',1);
        saveBookInCSV('Ubirajara',1);
        $book = readBookInCSVByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        deleteBookInCSV(2);
        $book = readBookInCSVByCode(2);
        $this->assertEmpty($book);
        $this->deleteBooksAndAuthors('csv');
    }    
}
