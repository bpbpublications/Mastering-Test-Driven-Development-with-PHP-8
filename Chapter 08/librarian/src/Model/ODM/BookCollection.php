<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookCollection
{
    private $database;

    public function __construct()
    {
        $database = Config::get('db')['database']; 
        $mongo = new \MongoDB\Client("mongodb://localhost:27017");
        $this->database = $mongo->$database;
    }

    public function save($title, int $authorCode)
    {	
        $insertOneResult = $this->database->books->insertOne([
            'code' => $this->database->books->countDocuments() + 1,
            'title' => $title,
            'author_code' => $authorCode        
        ]);
        return $insertOneResult->getInsertedCount() == 1;    
    }

    public function update(int $code, array $data)
    {
        $result = $this->database->books->updateOne(
            ['code' => $code],
            ['$set' => $data]
        );
        return $result->getModifiedCount() == 1;
    }

    public function delete(int $code)
    {
        $result = $this->database->books->deleteOne(['code' => $code]);
        return $result->getDeletedCount() == 1;
    }
}