<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Librarian\Model\Author;

/**
 * @covers Author
*/
#[CoversClass(Author::class)]
class AuthorTest extends TestCase
{
    public function testAuthorInstantiation()
    {
        $author = new Author();
        $author->setFirstName('Camilo');
        $author->setMiddleName('Castelo');
        $author->setLastName('Branco');

        $this->assertEquals('Camilo', $author->getFirstName());
        $this->assertEquals('Castelo', $author->getMiddleName());
        $this->assertEquals('Branco', $author->getLastName());
    }

    public function testAuthorCloning()
    {
        $author = new Author();
        $author->setFirstName('Federico');
        $author->setMiddleName('Garcia');
        $author->setLastName('Lorca');
        $authorCopy = $author;
        $authorClone = clone $author;
        $this->assertEquals($author->getFirstName(),$authorCopy->getFirstName());
        $this->assertEquals($author->getFirstName(),$authorClone->getFirstName());
        $this->assertEquals($author, $authorCopy);
        $this->assertEquals($author, $authorClone);
        $this->assertTrue($author == $authorCopy);
        $this->assertTrue($author == $authorClone);
        $this->assertTrue($author === $authorCopy);
        $this->assertFalse($author === $authorClone);
        $this->assertEquals(spl_object_hash($author),  spl_object_hash($authorCopy));
        $this->assertNotEquals(spl_object_hash($author), spl_object_hash($authorClone));
    }

    public function testAuthorConstructor()
    {
        $author = new Author('Richard', 'Brinsley', 'Sheridan');
        $this->assertEquals('Richard', $author->getFirstName());
        $this->assertEquals('Brinsley', $author->getMiddleName());
        $this->assertEquals('Sheridan', $author->getLastName());
    }

    public function testAuthorDestructor()
    {
        $author = new Author('Ana', 'Maria', 'Machado');
        $objectId = spl_object_id($author);
        unset($author);
        $output = shell_exec('grep "php: object ' . $objectId .  ' was destroyed" /var/log/syslog');
        $this->assertStringContainsString($objectId, $output);
    }

    public function testAttributeReadingAndWriting()
    {
        $author = new Author();
        $author->firstName = 'Sarat';
        $author->middleName = 'Chandra';
        $author->lastName = 'Chattopadhyay';
        $this->assertEquals('Sarat', $author->firstName);
        $this->assertEquals('Chandra', $author->middleName);
        $this->assertEquals('Chattopadhyay', $author->lastName);
    }

    public function testAuthorPrinting()
    {
        $author = new Author('Yahya','Taher','Abdullah');
        $sentence = $author . ' is the author of The Collar and the Bracelet';
        $this->assertStringContainsString('Yahya Taher Abdullah', $sentence);
    }

    public function testAuthorInvoker()
    {
        $author = new Author();
        $author('Ahmet', 'Hamdi', 'Tanpinar');
        $this->assertEquals('Ahmet', $author->getFirstName());
        $this->assertEquals('Hamdi', $author->getMiddleName());
        $this->assertEquals('Tanpinar', $author->getLastName());
    }    
}
