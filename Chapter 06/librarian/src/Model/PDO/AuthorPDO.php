<?php
namespace Librarian\Model\PDO;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorPDO
{
    private \PDO $pdo;

    public function __construct()
    {
        $db = getConfig()['db'];
        $this->pdo = new \PDO($db['dsn'], $db['username'], $db['password']);
    }

    public function save(string $firstName, string $middleName, string $lastName): bool
    {
        $sql = 'INSERT INTO authors(first_name,middle_name,last_name) values (:firstname,:middlename,:lastname)';    
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':firstname', $firstName);
        $statement->bindParam(':middlename', $middleName);
        $statement->bindParam(':lastname', $lastName);
        $statement->execute();
        return $statement->rowCount() == 1;
    }

    public function readByCode(int $code): array
    {        
        $sql = 'SELECT * FROM authors WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':code', $code);
        $statement->execute();
        $record = $statement->fetch(\PDO::FETCH_ASSOC);
        return (is_array($record) ? $record : []); 
    }

    public function readAuthors(): array
    {        
        $sql = 'SELECT * FROM authors';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $records = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return (is_array($records) ? $records : []);
    }
    
    public function update(int $code, array $data)
    {        
        $placeholders = '';
        $fields = [];
        foreach($data as $field => $value){
            $placeholder = str_replace('_','',$field);
            $placeholders .= $field . " = :$placeholder,";
            $fields[$placeholder] = $value;
        }
        $placeholders = substr($placeholders,0,strlen($placeholders)-1);        
        $sql = 'UPDATE authors SET ' . $placeholders . ' WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        foreach($fields as $field => &$value){
            $statement->bindParam(":$field", $value);
        }
        $statement->bindParam(':code', $code);
        $statement->execute();
        return $statement->rowCount() == 1;  
    }

    public function delete(int $code)
    {        
        $sql = 'DELETE FROM authors WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':code', $code);
        $statement->execute();
        return $statement->rowCount() == 1; 
    }    
}
