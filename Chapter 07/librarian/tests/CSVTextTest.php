<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use Librarian\Test\AbstractBackupTest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\CoversClass;
use Librarian\Model\Filesystem\AuthorCSV;
use Librarian\Model\Filesystem\BookCSV;
use Librarian\Util\Config;

/**
 * @covers AuthorCSV
 * @covers BookCSV
 */
#[CoversClass(AuthorCSV::class)]
#[CoversClass(BookCSV::class)]
class CSVTextTest extends AbstractBackupTest
{
    // author tests
    public function testSaveAuthor()
    {
        $authorCSV = new AuthorCSV();
        $this->assertTrue($authorCSV->save('Wells','Herbert','George'));
        $filepath = Config::get('author_csv_filepath');
        unlink($filepath);
    }
    
    public function testReadAuthor()
    {
        $authorCSV = new AuthorCSV();
        $authorCSV->save('Von Goethe','Wolfgang','Johann');
        $authorCSV->save('Fitzgerald','Scott','Francis');
        $authorCSV->save('Doyle','Arthur','Conan');
        $author = $authorCSV->readByCode(2);
        $this->assertEquals('Scott',$author['middle_name']);
        $filepath = Config::get('author_csv_filepath');
        unlink($filepath);
    }
    
    public function testReadAuthors()
    {
        $authorCSV = new AuthorCSV();
        $authorCSV->save('Shelley','Wollstonecraft','Mary');
        $authorCSV->save('Christie','Mary','Agatha');
        $authorCSV->save('Lispector','Pinkhasivna','Chaya');
        $authors = $authorCSV->readAll();
        $this->assertCount(3,$authors);
        $this->assertEquals('Agatha',$authors[1]['first_name']);
        $filepath = Config::get('author_csv_filepath');
        unlink($filepath);
    }
   
    public function testUpdateAuthor()
    {
        $authorCSV = new AuthorCSV();
        $authorCSV->save('Maupassant','de','Guy');
        $authorCSV->save('Saint-Exupéry','de','Antoine');
        $authorCSV->save('Balzac','de','Honoré');
        $author = $authorCSV->readByCode(1);
        $this->assertEquals('Guy',$author['first_name']);
        $authorCSV->update(1,[
            'last_name' => 'Raspe',
            'middle_name' => 'Erich',
            'first_name' => 'Rudolf'
        ]);
        $author = $authorCSV->readByCode(1);
        $this->assertEquals('Rudolf',$author['first_name']);        
        $filepath = Config::get('author_csv_filepath');
        unlink($filepath);
    }
   
    public function testDelete()
    {
        $authorCSV = new AuthorCSV();
        $authorCSV->save('Assis','de','Machado');
        $authorCSV->save('Alencar','de','José');
        $authorCSV->save('Queiroz','de','Rachel');
        $author = $authorCSV->readByCode(2);
        $this->assertEquals('Alencar',$author['last_name']);
        $authorCSV->delete(2);
        $author = $authorCSV->readByCode(2);
        $this->assertEmpty($author);        
        $filepath = Config::get('author_csv_filepath');
        unlink($filepath);
    }
    
    public function testSaveBook()
    {
        $authorCSV = new AuthorCSV();
        $bookCSV = new BookCSV();        
        $authorCSV->save('Wells','Herbert','George');
        $this->assertTrue($bookCSV->save('The Time Machine',1));
        $this->deleteBooksAndAuthors('csv');
    }
    
    public function testReadBook()
    {
        $authorCSV = new AuthorCSV();
        $bookCSV = new BookCSV();        
        $authorCSV->save('Von Goethe','Wolfgang','Johann');
        $bookCSV->save('Fausto',1);
        $book = $bookCSV->readByCode(1);
        $this->assertStringContainsString('Fausto',$book['title']);
        $this->deleteBooksAndAuthors('csv');
    }
   
    public function testReadBooks()
    {
        $authorCSV = new AuthorCSV();
        $bookCSV = new BookCSV();        
        $authorCSV->save('Christie','Mary','Agatha');
        $bookCSV->save('Murder on the Orient Express',1);
        $bookCSV->save('Death on the Nile',1);
        $bookCSV->save('Halloween Party',1);
        $books = $bookCSV->readAll();
        $this->assertCount(3,$books);
        $this->assertStringContainsString('Orient',$books[0]['title']);
        $this->deleteBooksAndAuthors('csv');
    }
    
    public function testUpdateBook()
    {
        $authorCSV = new AuthorCSV();
        $bookCSV = new BookCSV();        
        $authorCSV->save('Balzac','de','Honoré');
        $bookCSV->save('La Vendetta',1);
        $bookCSV->save('Le Contrat de mariage',1);
        $bookCSV->save('La Femme de trente ans',1);
        $book = $bookCSV->readByCode(2);
        $this->assertStringContainsString('mariage',$book['title']);
        $bookCSV->update(2,[
            'title' => 'Un début dans la vie'
        ]);
        $book = $bookCSV->readByCode(2);
        $this->assertStringContainsString('vie',$book['title']);
        $this->deleteBooksAndAuthors('csv');
    }
    
    public function testDeleteBook()
    {
        $authorCSV = new AuthorCSV();
        $bookCSV = new BookCSV();        
        $authorCSV->save('Alencar','de','José');
        $bookCSV->save('O Guarani',1);
        $bookCSV->save('Iracema',1);
        $bookCSV->save('Ubirajara',1);
        $book = $bookCSV->readByCode(2);
        $this->assertStringContainsString('Iracema',$book['title']);
        $bookCSV->delete(2);
        $book = $bookCSV->readByCode(2);
        $this->assertEmpty($book);
        $this->deleteBooksAndAuthors('csv');
    }    
}
