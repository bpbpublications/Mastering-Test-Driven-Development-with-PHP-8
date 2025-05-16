<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class Collection
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function drop()
    {
        $database = $this->getConnection();
        $name = $this->name;
        $collection = $database->$name;
        $collection->drop();
    }

    private function getConnection()
    {   
        $database = Config::get('db')['database'];
        $mongo = new \MongoDB\Client("mongodb://localhost:27017");
        return $mongo->$database;
    }    

}