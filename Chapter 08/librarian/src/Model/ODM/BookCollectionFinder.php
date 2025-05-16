<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
use Librarian\Model\Book;
use Librarian\Model\Author;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookCollectionFinder
{
    private $database;

    public function __construct()
    {
        $database = Config::get('db')['database']; 
        $mongo = new \MongoDB\Client("mongodb://localhost:27017");
        $this->database = $mongo->$database;
    }

    public function readByCode(int $code)
    {
        $result = $this->database->books->findOne(['code' => $code]);
        if (is_null($result)){
            return new Book();
        }
        return new Book($code, $result->title, new Author($result->author_code));
    }

    public function readAll()
    {
        $result = $this->database->books->find();
        if (is_null($result)){
            return new BookRowSet();
        }
        return new BookRowSet($result->toArray());
    }

    public function update(int $code, array $data)
    {
        $result = $this->database->books->updateOne(
            ['code' => $code],
            ['$set' => $data]
        );
        return $result->getModifiedCount() == 1;
    }
}