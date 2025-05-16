<?php
namespace Librarian\Model\ORM;
use Librarian\Util\Config;

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
final class Table
{
    private $table;
    private \PDO $connection;

    public function __construct(string $table)
    {
        $this->table = $table;
        $db = Config::get('db');
        $this->connection = new \PDO($db['dsn'], $db['username'], $db['password']);
    }
    
    public function truncate()
    {
        $sql = 'DELETE FROM ' . $this->table;
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $sql = 'ALTER TABLE ' . $this->table . ' AUTO_INCREMENT = 1';
        $statement = $this->connection->prepare($sql);
        return $statement->execute();
    }
}
