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
        $author->firsName= 'José';
        $author->middleName = 'Emílio';
        $author->lastName = 'Pacheco';
        $book = new Book();
        $book->title = 'El viento distante';
        $book->author = $author;
        $this->assertEquals('El viento distante', $book->title);
        $this->assertEquals('Pacheco', $book->author->lastName);
    }
    
    public function testBookConstructor()
    {
        $author = new Author(0,'Richard', 'Brinsley', 'Sheridan');
        $book = new Book(0,'Rivals', $author);
        $this->assertEquals('Rivals', $book->title);
        $this->assertEquals('Sheridan', $book->author->lastName);
    }    
}
