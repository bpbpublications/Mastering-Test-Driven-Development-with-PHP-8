<?php
namespace Librarian\Model\ORM;
use Librarian\Model\Author;
use Librarian\Model\RowGatewayInterface;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorRowGateway extends Author implements RowGatewayInterface
{
    use DatabaseTrait;

    public function __construct(int $code = 0, string $firstName = '', string $middleName = '', string $lastName = '')
    {
        parent::__construct($code, $firstName, $middleName, $lastName);
        $this->initDatabase();
    }

    public function save(): bool
    {
        $sql = 'INSERT INTO authors(first_name,middle_name,last_name) values (:firstname,:middlename,:lastname)';    
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':firstname', $this->firstName);
        $statement->bindParam(':middlename', $this->middleName);
        $statement->bindParam(':lastname', $this->lastName);
        $statement->execute();
        return $statement->rowCount() == 1;
    }
  
    public function update(): bool
    {   
        $data = ['code' => $this->code];
        $data['first_name'] = $this->firstName;
        $data['middle_name'] = $this->middleName;
        $data['last_name'] = $this->lastName;
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
        $statement->bindParam(':code', $this->code);
        $statement->execute();
        return $statement->rowCount() == 1;  
    }

    public function delete(): bool
    {        
        $sql = 'DELETE FROM authors WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':code', $this->code);
        $statement->execute();
        return $statement->rowCount() == 1; 
    }
    
    public function isEmpty(): bool
    {
        return empty($this->code) &&
        empty($this->firstName) &&
        empty($this->middleName) &&
        empty($this->lastName);
    }
}
