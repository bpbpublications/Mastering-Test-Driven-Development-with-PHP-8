<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use Librarian\Test\AbstractBackupTest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\CoversClass;
use Librarian\Model\Filesystem\AuthorJSON;
use Librarian\Model\Filesystem\BookJSON;
use Librarian\Util\Config;
use Librarian\Model\Filesystem\AuthorJSONFinder;
use Librarian\Model\Filesystem\BookJSONFinder;

// author tests
/**
 * @covers AuthorJSON
 * @covers BookJSON
 */
#[CoversClass(AuthorJSON::class)]
#[CoversClass(BookJSON::class)]
class JSONTextTest extends AbstractBackupTest
{
    public function testSaveAuthor()
    {
        $this->assertTrue((new AuthorJSON())->save('Wells','Herbert','George'));
        $filepath = Config::get('author_json_filepath');
        unlink($filepath);
    }
    
    public function testReadAuthor()
    {
        $authorJSON = new AuthorJSON();
        $authorJSON->save('Von Goethe','Wolfgang','Johann');
        $authorJSON->save('Fitzgerald','Scott','Francis');
        $authorJSON->save('Doyle','Arthur','Conan');
        $author = (new AuthorJSONFinder())->readByCode(2);
        $this->assertEquals('Scott',$author->middleName);
        $filepath = Config::get('author_json_filepath');
        unlink($filepath);
    }
    
    public function testReadAuthors()
    {
        $authorJSON = new AuthorJSON();
        $authorJSON->save('Shelley','Wollstonecraft','Mary');
        $authorJSON->save('Christie','Mary','Agatha');
        $authorJSON->save('Lispector','Pinkhasivna','Chaya');
        $authors = (new AuthorJSONFinder())->readAll();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors->get(1)->firstName);
        $filepath = Config::get('author_json_filepath');
        unlink($filepath);
    }
   
    public function testUpdateAuthor()
    {
        $authorJSON = new AuthorJSON();
        $authorJSON->save('Maupassant','de','Guy');
        $authorJSON->save('Saint-Exupéry','de','Antoine');
        $authorJSON->save('Balzac','de','Honoré');
        $authorJSONFinder = new AuthorJSONFinder();
        $author = $authorJSONFinder->readByCode(1);
        $this->assertEquals('Guy',$author->firstName);
        $authorJSON->update(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = $authorJSONFinder->readByCode(1);
        $this->assertEquals('Rudolf',$author->firstName);
        $filepath = Config::get('author_json_filepath');
        unlink($filepath);
    }
    
    public function testDeleteAuthor()
    {
        $authorJSON = new AuthorJSON();
        $authorJSON->save('Assis','de','Machado');
        $authorJSON->save('Alencar','de','José');
        $authorJSON->save('Queiroz','de','Rachel');
        $authorJSONFinder = new AuthorJSONFinder();        
        $author = $authorJSONFinder->readByCode(2);
        $this->assertEquals('Alencar',$author->lastName);
        $authorJSON->delete(2);
        $author = $authorJSONFinder->readByCode(2);
        $this->assertEmpty($author->code);
        $filepath = Config::get('author_json_filepath');
        unlink($filepath);
    }
    // book tests
    public function testSaveBook()
    {
        (new AuthorJSON())->save('Wells','Herbert','George');
        $this->assertTrue((new BookJSON())->save('The Time Machine',1));
        $this->deleteBooksAndAuthors('json');
    }
  
    public function testReadBookInJSON()
    {
        (new AuthorJSON())->save('Von Goethe','Wolfgang','Johann');
        (new BookJSON())->save('Fausto',1);
        $book = (new BookJSONFinder())->readByCode(1);
        $this->assertStringContainsString('Fausto',$book->title);
        $this->deleteBooksAndAuthors('json');
    }
    
    public function testReadBooks()
    {
        $bookJSON = new BookJSON();
        (new AuthorJSON())->save('Christie','Mary','Agatha');
        $bookJSON->save('Murder on the Orient Express',1);
        $bookJSON->save('Death on the Nile',1);
        $bookJSON->save('Halloween Party',1); 
        $books = (new BookJSONFinder())->readAll();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books->get(0)->title);
        $this->deleteBooksAndAuthors('json');
    }
    
    public function testUpdate()
    {
        $bookJSON = new BookJSON();
        (new AuthorJSON())->save('Balzac','de','Honoré');
        $bookJSON->save('La Vendetta',1);
        $bookJSON->save('Le Contrat de mariage',1);
        $bookJSON->save('La Femme de trente ans',1);
        $bookJSONFinder = new BookJSONFinder();
        $book = $bookJSONFinder->readByCode(2);
        $this->assertStringContainsString('mariage',$book->title);
        $bookJSON->update(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = $bookJSONFinder->readByCode(2);
        $this->assertStringContainsString('vie',$book->title);
        $this->deleteBooksAndAuthors('json');        
    }
    
    public function testDelete()
    {
        $authorJSON = new AuthorJSON();
        $bookJSON = new BookJSON();
        $authorJSON->save('Alencar','de','José');
        $bookJSON->save('O Guarani',1);
        $bookJSON->save('Iracema',1);
        $bookJSON->save('Ubirajara',1);
        $bookJSONFinder = new BookJSONFinder();
        $book = $bookJSONFinder->readByCode(2);
        $this->assertStringContainsString('Iracema',$book->title);
        $bookJSON->delete(2);
        $book = $bookJSONFinder->readByCode(2);
        $this->assertEmpty($book->code);
        $this->deleteBooksAndAuthors('json');
    }
}
