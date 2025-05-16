<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
use Librarian\Model\Book;
use Librarian\Model\Author;
use Librarian\Model\BookRowSet;
use Librarian\Model\FinderInterface;
use Librarian\Model\AbstractCode;
use Librarian\Model\AbstractRowSet;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookCollectionFinder extends AbstractCollection implements FinderInterface
{
    public function readByCode(int $code): AbstractCode
    {
        $result = $this->database->books->findOne(['code' => $code]);
        if (is_null($result)){
            return new Book();
        }
        return new Book($code, $result->title, new Author($result->author_code));
    }

    public function readAll(): AbstractRowSet
    {
        $result = $this->database->books->find();
        if (is_null($result)){
            return new BookRowSet();
        }
        return new BookRowSet($result->toArray());
    }
}