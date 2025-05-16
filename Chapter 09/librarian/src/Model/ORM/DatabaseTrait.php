<?php
namespace Librarian\Model\ORM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
trait DatabaseTrait
{
    protected \PDO $pdo;

    public function initDatabase()
    {
        $db = Config::get('db');
        $this->pdo = new \PDO($db['dsn'], $db['username'], $db['password']);
    }
}
