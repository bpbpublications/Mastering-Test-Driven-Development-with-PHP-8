<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use Librarian\Test\AbstractBackupTest;
use PHPUnit\Framework\Attributes\CoversNothing;

class TxtTest extends AbstractBackupTest
{
    // author tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthorInPlainText()
    {
        $this->assertTrue(saveAuthorInPlainText('Wells','Herbert','George'));
        $filepath = getConfig()['author_plaintext_filepath'];
        unlink($filepath);
    }
  
    /**
     * @coversNothing
     */    
     #[CoversNothing()]
    public function testReadAuthorInPlainText()
    {
        saveAuthorInPlainText('Von Goethe','Wolfgang','Johann');
        saveAuthorInPlainText('Fitzgerald','Scott','Francis');
        saveAuthorInPlainText('Doyle','Arthur','Conan');
        $author = readAuthorInPlainTextByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        $filepath = getConfig()['author_plaintext_filepath'];
        unlink($filepath);
    }

    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadAuthorsInPlainText()
    {
        saveAuthorInPlainText('Shelley','Wollstonecraft','Mary');
        saveAuthorInPlainText('Christie','Mary','Agatha');
        saveAuthorInPlainText('Lispector','Pinkhasivna','Chaya');
        $authors = readAuthorsInPlainText();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        $filepath = getConfig()['author_plaintext_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateAuthorInPlainText()
    {
        saveAuthorInPlainText('Maupassant','de','Guy');
        saveAuthorInPlainText('Saint-Exupéry','de','Antoine');
        saveAuthorInPlainText('Balzac','de','Honoré');
        $author = readAuthorInPlainTextByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        updateAuthorInPlainText(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = readAuthorInPlainTextByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);
        $filepath = getConfig()['author_plaintext_filepath'];
        unlink($filepath);
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteAuthorInPlainText()
    {
        saveAuthorInPlainText('Assis','de','Machado');
        saveAuthorInPlainText('Alencar','de','José');
        saveAuthorInPlainText('Queiroz','de','Rachel');
        $author = readAuthorInPlainTextByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        deleteAuthorInPlainText(2);
        $author = readAuthorInPlainTextByCode(2);
        $this->assertEmpty($author);
        $filepath = getConfig()['author_plaintext_filepath'];
        unlink($filepath);
    }
    
    // book tests
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBookInPlainText()
    {
        saveAuthorInPlainText('Wells','Herbert','George');
        $this->assertTrue(saveBookInPlainText('The Time Machine',1));
        $this->deleteBooksAndAuthors('txt');
    }
 
    /**
     * @coversNothing
     */    
     #[CoversNothing()]
    public function testReadBookInPlainText()
    {
        saveAuthorInPlainText('Von Goethe','Wolfgang','Johann');        
        saveBookInPlainText('Fausto',1);
        $book = readBookInPlainTextByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        $this->deleteBooksAndAuthors('txt');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testReadBooksInPlainText()
    {
        saveAuthorInPlainText('Christie','Mary','Agatha');
        saveBookInPlainText('Murder on the Orient Express',1);
        saveBookInPlainText('Death on the Nile',1);
        saveBookInPlainText('Halloween Party',1);
        $books = readBooksInPlainText();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        $this->deleteBooksAndAuthors('txt');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testUpdateBookInPlainText()
    {
        saveAuthorInPlainText('Balzac','de','Honoré');
        saveBookInPlainText('La Vendetta',1);
        saveBookInPlainText('Le Contrat de mariage',1);
        saveBookInPlainText('La Femme de trente ans',1);
        $book = readBookInPlainTextByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        updateBookInPlainText(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = readBookInPlainTextByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        $this->deleteBooksAndAuthors('txt');
    }
    
    /**
     * @coversNothing
     */    
    #[CoversNothing()]
    public function testDeleteBookInPlainText()
    {
        saveAuthorInPlainText('Alencar','de','José');
        saveBookInPlainText('O Guarani',1);
        saveBookInPlainText('Iracema',1);
        saveBookInPlainText('Ubirajara',1);
        $book = readBookInPlainTextByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        deleteBookInPlainText(2);
        $book = readBookInPlainTextByCode(2);
        $this->assertEmpty($book);
        $this->deleteBooksAndAuthors('txt');
    }  
}
