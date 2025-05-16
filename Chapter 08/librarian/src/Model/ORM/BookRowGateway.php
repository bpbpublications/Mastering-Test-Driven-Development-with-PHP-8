<?php
namespace Librarian\Model\ORM;
use Librarian\Model\Author;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookRowGateway
{
    public int $code;
    public string $title;
    public Author $author;
    private \PDO $pdo;

    public function __construct(int $code = 0, string $title = '', int $authorCode = 0)
    {
        $this->code = $code;
        $this->title = $title;
        $this->author = new Author($authorCode);
        $db = Config::get('db');
        $this->pdo = new \PDO($db['dsn'], $db['username'], $db['password']);
    }

    public function save(): bool
    {
        $sql = 'INSERT INTO books(title,author_code) values (:title,:authorcode)';    
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':authorcode', $this->author->code);
        $statement->execute();
        return $statement->rowCount() == 1;
    }
   
    public function update(): bool
    {   
        $data = get_object_vars($this);
        unset($data['pdo']);
        $data['author_code'] = $data['author']->code;
        unset($data['author']);
        $placeholders = '';
        $fields = [];
        foreach($data as $field => $value){
            $placeholder = str_replace('_','',$field);
            $placeholders .= $field . " = :$placeholder,";
            $fields[$placeholder] = $value;
        }
        $placeholders = substr($placeholders,0,strlen($placeholders)-1);        
        $sql = 'UPDATE books SET ' . $placeholders . ' WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        foreach($fields as $field => &$value){
            $statement->bindParam(":$field", $value);
        }
        $statement->execute();
        return $statement->rowCount() == 1;
    }

    public function delete(): bool
    {        
        $sql = 'DELETE FROM books WHERE code = :code';
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':code', $this->code);
        $statement->execute();
        return $statement->rowCount() == 1;
    }

    public function isEmpty(): bool
    {
        return empty($this->code) &&
            empty($this->title);
    }
}
