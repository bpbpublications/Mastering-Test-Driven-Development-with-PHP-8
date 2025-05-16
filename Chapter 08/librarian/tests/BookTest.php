<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Librarian\Model\Author;
use Librarian\Model\Book;

/**
 * @covers Author
 * @covers Book
 * */
#[CoversClass(Author::class)]
#[CoversClass(Book::class)]
class BookTest extends TestCase
{
    public function testBookInstantiation()
    {
        $author = new Author();
        $author->setFirstName('José');
        $author->setMiddleName('Emílio');
        $author->setLastName('Pacheco');
        $book = new Book();
        $book->setTitle('El viento distante');
        $book->setAuthor($author);
        $this->assertEquals('El viento distante', $book->getTitle());
        $this->assertEquals('Pacheco', $book->getAuthor()->getLastName());
    }
    
    public function testBookConstructor()
    {
        $author = new Author(0,'Richard', 'Brinsley', 'Sheridan');
        $book = new Book(0,'Rivals', $author);
        $this->assertEquals('Rivals', $book->getTitle());
        $this->assertEquals('Sheridan', $book->getAuthor()->getLastName());
    }    
}
