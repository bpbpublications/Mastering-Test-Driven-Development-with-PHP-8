<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

class StorageTest extends TestCase
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
    
    private function deleteBooksAndAuthors(string $type)
    {
        $type = ($type == 'txt' ? 'plaintext' : $type);
        $filepath = getConfig()["book_{$type}_filepath"];
        unlink($filepath);
        $filepath = getConfig()["author_{$type}_filepath"];
        unlink($filepath);
    }
}
