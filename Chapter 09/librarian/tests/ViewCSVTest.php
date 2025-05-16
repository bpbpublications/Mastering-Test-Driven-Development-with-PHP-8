<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Fgsl\Rest\Rest;
use Librarian\Test\PHPServer;

class ViewCSVTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        putenv('LIBRARIAN_TEST_ENVIRONMENT=true');
        PHPServer::getInstance()->start();
        replaceConfigFileContent("'storage_format' => 'txt'","'storage_format' => 'csv'");
    }

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testIndex()
    {
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php',200);
        $this->assertStringContainsString('Librarian',$response);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query("/html/body/ul");
        $this->assertEquals('Authors',$elements[0]->childNodes[1]->nodeValue);
    }
    //authors
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testListAuthors()
    {
        saveAuthor('Márquez','García','Gabriel');
        saveAuthor('Borges','Luis','Jorge');
        saveAuthor('Llosa','Vargas','Mario');
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=author&a=list',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query("/html/body/h1");
        $this->assertEquals('Authors',$elements[0]->childNodes[0]->nodeValue);
        $this->assertStringContainsString('Jorge Luis Borges',$response);
        clearEntity('author');
    }

     /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testListNoAuthors()
    {
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=author&a=list',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query("/html/body/h1");
        $this->assertEquals('Authors',$elements[0]->childNodes[0]->nodeValue);
        clearEntity('author');
    }   

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testNewAuthor()
    {
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=author&a=edit',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $nodeList = $xpath->query('//input[@type="text"]');
        $this->assertEquals(3,$nodeList->length);
        $node = $nodeList->item(0);
        $this->assertEmpty($node->nodeValue);
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testEditAuthor()
    {
        saveAuthor('Sharma','Lakshmi','Raj');
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008//index.php?c=author&a=edit&code=1',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $nodeList = $xpath->query('//input[@type="text"]');
        $this->assertEquals(3,$nodeList->length);
        $node = $nodeList->item(0);
        $this->assertEquals('Raj',$node->getAttribute('value'));
        clearEntity('author');
    }

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveAuthor()
    {
        $data = [
            'first_name' => 'Fyodor',
            'middle_name' => 'Mikhailovich',
            'last_name' => 'Dostoevsky'
        ];
        $rest = new Rest();
        $response = $rest->doPost($data, [],'localhost:8008/index.php?c=author&a=save',302);
        $this->assertStringContainsString('Record saved successfully!',$response);
        clearEntity('author');
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testUpdateAuthor()
    {
        $data = [
            'first_name' => 'Boris',
            'middle_name' => 'Leonidovich',
            'last_name' => 'Pasternak'
        ];
        saveAuthor($data['last_name'],$data['middle_name'],$data['first_name']);
        $data['code'] = 1;
        $data['last_name'] = 'Neigauz';
        $rest = new Rest();
        $response = $rest->doPost($data, [],'localhost:8008/index.php?c=author&a=save',302);
        $this->assertStringContainsString('Record updated successfully!',$response);
        $author = getAuthorByCode(1);
        $this->assertEquals('Neigauz',$author->lastName);
        clearEntity('author');
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testDeleteAuthor()
    {
        $data = [
            'first_name' => 'Vladimir',
            'middle_name' => 'Vladimirovich',
            'last_name' => 'Nabokov'
        ];
        saveAuthor($data['last_name'],$data['middle_name'],$data['first_name']);
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=author&a=delete&code=1',302);
        $this->assertStringContainsString('Record deleted successfully!',$response);
        $author = getAuthorByCode(1);
        $this->assertEmpty($author->lastName);
        clearEntity('author');
    }

    //books
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testListBooks()
    {
        saveAuthor('Márquez','García','Gabriel');
        saveBook('La hojarasca',1);
        saveBook('Cien años de soledad',1);
        saveBook('Crónica de una muerte anunciada',1);                
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=book&a=list',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query("/html/body/h1");
        $this->assertEquals('Books',$elements[0]->childNodes[0]->nodeValue);
        $this->assertStringContainsString('muerte anunciada',$response);
        clearEntity('book');
        clearEntity('author');        
    }

     /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testListNoBooks()
    {
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=book&a=list',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query("/html/body/h1");
        $this->assertEquals('Books',$elements[0]->childNodes[0]->nodeValue);
        clearEntity('book');
    }   

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testNewBook()
    {
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=book&a=edit',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $nodeList = $xpath->query('//input[@type="text"]');
        $this->assertEquals(1,$nodeList->length);
        $node = $nodeList->item(0);
        $this->assertEmpty($node->nodeValue);
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testEditBook()
    {
        saveAuthor('Sharma','Lakshmi','Raj');
        saveBook('Saba and Nisha',1);
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=book&a=edit&code=1',200);
        $doc = new DomDocument();
        $doc->loadHTML($response);
        $xpath = new DOMXpath($doc);
        $nodeList = $xpath->query('//input[@type="text"]');
        $this->assertEquals(1,$nodeList->length);
        $node = $nodeList->item(0);
        $this->assertEquals('Saba and Nisha',$node->getAttribute('value'));
        clearEntity('book');
        clearEntity('author');
    }

    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testSaveBook()
    {
        $data = [
            'first_name' => 'Fyodor',
            'middle_name' => 'Mikhailovich',
            'last_name' => 'Dostoevsky'
        ];
        saveAuthor($data['last_name'],$data['middle_name'],$data['first_name']);
        $data = [
            'title' => 'Crime and Punishment',
            'author_code' => 1
        ];
        saveBook($data['title'],$data['author_code']);
        $rest = new Rest();
        $response = $rest->doPost($data, [],'localhost:8008/index.php?c=book&a=save',302);
        $this->assertStringContainsString('Record saved successfully!',$response);
        clearEntity('book');
        clearEntity('author');
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testUpdateBook()
    {
        $data = [
            'first_name' => 'Boris',
            'middle_name' => 'Leonidovich',
            'last_name' => 'Pasternak'
        ];
        saveAuthor($data['last_name'],$data['middle_name'],$data['first_name']);
        $data = [
            'title' => 'Doktor Jivago',
            'author_code' => 1
        ];
        saveBook($data['title'],$data['author_code']);
        $data['code'] = 1;
        $data['title'] = 'Stihi';
        $rest = new Rest();
        $response = $rest->doPost($data, [],'localhost:8008/index.php?c=book&a=save',302);
        $this->assertStringContainsString('Record updated successfully!',$response);
        $book = getBookByCode(1);
        $this->assertEquals('Stihi',$book->title);
        clearEntity('book');
        clearEntity('author');
    }
    
    /**
     * @coversNothing
     */
    #[CoversNothing()]
    public function testDeleteBook()
    {
        $data = [
            'first_name' => 'Vladimir',
            'middle_name' => 'Vladimirovich',
            'last_name' => 'Nabokov'
        ];
        saveAuthor($data['last_name'],$data['middle_name'],$data['first_name']);
        saveBook('Lolita',1);
        $rest = new Rest();
        $response = $rest->doGet([],'localhost:8008/index.php?c=book&a=delete&code=1',302);
        $this->assertStringContainsString('Record deleted successfully!',$response);
        $book = getBookByCode(1);
        $this->assertEmpty($book->title);
        clearEntity('book');
        clearEntity('author');
    }

    public static function tearDownAfterClass():void
    {
        PHPServer::getInstance()->stop();
        replaceConfigFileContent("'storage_format' => 'csv'","'storage_format' => 'txt'");
        putenv('LIBRARIAN_TEST_ENVIRONMENT=false');
    }
}
