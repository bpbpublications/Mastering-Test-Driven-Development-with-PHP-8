<?php
namespace Librarian\Model\ORM;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorFinder
{
    private \PDO $pdo;

    public function __construct()
    {
        $db = getConfig()['db'];
        $this->pdo = new \PDO($db['dsn'], $db['username'], $db['password']);
    }

    public function readByCode(int $code): AuthorRowGateway
    {        
        $sql = 'SELECT * FROM authors WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':code', $code);
        $statement->execute();
        $record = $statement->fetch(\PDO::FETCH_ASSOC);
        $author = new AuthorRowGateway();
        if (is_array($record)){
            $author->code = $record['code'];
            $author->firstName = $record['first_name'];
            $author->middleName = $record['middle_name'];
            $author->lastName = $record['last_name'];
        }        
        return $author; 
    }

    public function readAll(): AuthorRowSet
    {        
        $sql = 'SELECT * FROM authors';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $records = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $authors = new AuthorRowSet();
        if (is_array($records)) {
            foreach($records as $record){
                $authors->add(new AuthorRowGateway(
                    $record['code'],
                    $record['first_name'],
                    $record['middle_name'],
                    $record['last_name']
                    )
                );
            }
        }
        return $authors;
    }   
}
