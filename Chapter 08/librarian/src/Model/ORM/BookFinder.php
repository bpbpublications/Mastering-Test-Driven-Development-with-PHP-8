<?php
namespace Librarian\Model\ORM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookFinder
{
    private \PDO $pdo;

    public function __construct()
    {
        $db = Config::get('db');
        $this->pdo = new \PDO($db['dsn'], $db['username'], $db['password']);
    }

    public function readByCode(int $code): BookRowGateway
    {        
        $sql = 'SELECT * FROM books WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':code', $code);
        $statement->execute();
        $record = $statement->fetch(\PDO::FETCH_ASSOC);
        $book = new BookRowGateway();
        if (is_array($record)) {
            $book->code = $record['code'];
            $book->title = $record['title'];
            $book->author->code = $record['author_code'];
        } 
        return $book;

    }

    public function readAll(): BookRowSet
    {        
        $sql = 'SELECT * FROM books';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $records = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $books = new BookRowSet();
        if (is_array($records)){
            foreach($records as $record){
                $books->add(new BookRowGateway(
                    $record['code'],
                    $record['title'],
                    $record['author_code']
                ));
            }
        }
        return $books;
    }
}
