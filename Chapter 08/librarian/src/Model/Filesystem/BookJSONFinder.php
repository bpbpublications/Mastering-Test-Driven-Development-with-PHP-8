<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
use Librarian\Model\Book;
use Librarian\Model\Author;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookJSONFinder extends AbstractBookFilesystem
{
    public function readByCode(int $code): Book
    {
        $filepath = Config::get('book_json_filepath');
        $content = file_get_contents($filepath);
        $books = json_decode($content);
        $books = is_null($books) ? [] : $books;
        foreach($books as $book) {
            if ((int) $book->code == $code) {
                return new Book(
                    (int)$book->code,
                    $book->title,
                    new Author($book->author_code)
                );
            }
        }
        return new Book();
    }

    function readAll(): BookRowSet
    {
        $filepath = getConfig()['book_json_filepath'];
        $content = file_get_contents($filepath);
        $records = json_decode($content);
        $records = is_null($records) ? [] : $records;
        $books = new BookRowSet();
        foreach($records as $book) {
            $books->add(new Book(
                (int)$book->code,
                $book->title,
                new Author($book->author_code)
            ));
        }
        return $books;
    }
}