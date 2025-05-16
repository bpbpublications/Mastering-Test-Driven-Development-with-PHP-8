<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use Librarian\Test\AbstractBackupTest;
use PHPUnit\Framework\Attributes\CoversClass;
use Librarian\Model\Filesystem\AuthorPlainText;
use Librarian\Model\Filesystem\BookPlainText;
use Librarian\Util\Config;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversClass(AuthorPlainText::class)]
#[CoversClass(BookPlainText::class)]
/**
 * @covers AuthorPlainText
 * @covers BookPlainText
*/    
class PlainTextTest extends AbstractBackupTest
{
    // author tests
    public function testSaveAuthor()
    {
        $authorPlainText = new AuthorPlainText();
        $this->assertTrue($authorPlainText->save('Wells','Herbert','George'));
        $filepath = Config::get('author_plaintext_filepath');
        unlink($filepath);
    }
  
    public function testReadAuthor()
    {
        $authorPlainText = new AuthorPlainText();
        $authorPlainText->save('Von Goethe','Wolfgang','Johann');
        $authorPlainText->save('Fitzgerald','Scott','Francis');
        $authorPlainText->save('Doyle','Arthur','Conan');
        $author = $authorPlainText->readByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        $filepath = Config::get('author_plaintext_filepath');
        unlink($filepath);
    }

    public function testReadAuthors()
    {   
        $authorPlainText = new AuthorPlainText();     
        $authorPlainText->save('Shelley','Wollstonecraft','Mary');
        $authorPlainText->save('Christie','Mary','Agatha');
        $authorPlainText->save('Lispector','Pinkhasivna','Chaya');
        $authors = $authorPlainText->readAll();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        $filepath = Config::get('author_plaintext_filepath');
        unlink($filepath);
    }
    
    public function testUpdateAuthor()
    {
        $authorPlainText = new AuthorPlainText();
        $authorPlainText->save('Maupassant','de','Guy');
        $authorPlainText->save('Saint-Exupéry','de','Antoine');
        $authorPlainText->save('Balzac','de','Honoré');
        $author = $authorPlainText->readByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        $authorPlainText->update(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = $authorPlainText->readByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);
        $filepath = Config::get('author_plaintext_filepath');
        unlink($filepath);
    }
    
    public function testDeleteAuthor()
    {
        $authorPlainText = new AuthorPlainText();
        $authorPlainText->save('Assis','de','Machado');
        $authorPlainText->save('Alencar','de','José');
        $authorPlainText->save('Queiroz','de','Rachel');
        $author = $authorPlainText->readByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        $authorPlainText->delete(2);
        $author = $authorPlainText->readByCode(2);
        $this->assertEmpty($author);
        $filepath = Config::get('author_plaintext_filepath');
        unlink($filepath);
    }
    
    public function testSaveBook()
    {
        $authorPlainText = new AuthorPlainText();
        $bookPlainText = new BookPlainText();
        $authorPlainText->save('Wells','Herbert','George');
        $this->assertTrue($bookPlainText->save('The Time Machine',1));
        $this->deleteBooksAndAuthors('txt');
    }
 
    public function testReadBook()
    {
        $authorPlainText = new AuthorPlainText();
        $bookPlainText = new BookPlainText();
        $authorPlainText->save('Von Goethe','Wolfgang','Johann');        
        $bookPlainText->save('Fausto',1);
        $book = $bookPlainText->readByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        $this->deleteBooksAndAuthors('txt');
    }
    
    public function testReadBooks()
    {
        $authorPlainText = new AuthorPlainText();
        $bookPlainText = new BookPlainText();
        $authorPlainText->save('Christie','Mary','Agatha');
        $bookPlainText->save('Murder on the Orient Express',1);
        $bookPlainText->save('Death on the Nile',1);
        $bookPlainText->save('Halloween Party',1);
        $books = $bookPlainText->readAll();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        $this->deleteBooksAndAuthors('txt');
    }
    
    public function testUpdateBook()
    {
        $authorPlainText = new AuthorPlainText();
        $bookPlainText = new BookPlainText();
        $authorPlainText->save('Balzac','de','Honoré');
        $bookPlainText->save('La Vendetta',1);
        $bookPlainText->save('Le Contrat de mariage',1);
        $bookPlainText->save('La Femme de trente ans',1);
        $book = $bookPlainText->readByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        $bookPlainText->update(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = $bookPlainText->readByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        $this->deleteBooksAndAuthors('txt');
    }
    
    public function testDeleteBook()
    {
        $authorPlainText = new AuthorPlainText();
        $bookPlainText = new BookPlainText();
        $authorPlainText->save('Alencar','de','José');
        $bookPlainText->save('O Guarani',1);
        $bookPlainText->save('Iracema',1);
        $bookPlainText->save('Ubirajara',1);
        $book = $bookPlainText->readByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        $bookPlainText->delete(2);
        $book = $bookPlainText->readByCode(2);
        $this->assertEmpty($book);
        $this->deleteBooksAndAuthors('txt');
    }  
}
