<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
use Librarian\Model\Author;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorCollectionFinder
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
        $result = $this->database->authors->findOne(['code' => $code]);
        if (is_null($result)){
            return new Author();
        }
        return new Author($code, $result['first_name'],$result['middle_name'],$result['last_name']);
    }

    public function readAll()
    {
        $result = $this->database->authors->find();
        if (is_null($result)){
            return new AuthorRowSet();
        }
        return new AuthorRowSet($result->toArray());
    }
}