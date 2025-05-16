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
        $author->firstName = 'Camilo';
        $author->middleName = 'Castelo';
        $author->lastName = 'Branco';

        $this->assertEquals('Camilo', $author->firstName);
        $this->assertEquals('Castelo', $author->middleName);
        $this->assertEquals('Branco', $author->lastName);
    }

    public function testAuthorCloning()
    {
        $author = new Author();
        $author->firstName = 'Federico';
        $author->middleName = 'Garcia';
        $author->lastName = 'Lorca';
        $authorCopy = $author;
        $authorClone = clone $author;
        $this->assertEquals($author->firstName,$authorCopy->firstName);
        $this->assertEquals($author->firstName,$authorClone->firstName);
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
        $author = new Author(0,'Richard', 'Brinsley', 'Sheridan');
        $this->assertEquals('Richard', $author->firstName);
        $this->assertEquals('Brinsley', $author->middleName);
        $this->assertEquals('Sheridan', $author->lastName);
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
}
