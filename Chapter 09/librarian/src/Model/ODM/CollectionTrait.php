<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
trait CollectionTrait
{
    protected $database;

    protected function initDatabase(): void
    {
        $database = Config::get('db')['database']; 
        $mongo = new \MongoDB\Client("mongodb://localhost:27017");
        $this->database = $mongo->$database;
    }
}