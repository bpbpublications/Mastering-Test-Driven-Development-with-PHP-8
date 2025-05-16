<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
use Librarian\Model\Book;
use Librarian\Model\Author;
use Librarian\Model\RowGatewayInterface;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookCollectionRowGateway extends Book implements RowGatewayInterface
{
    use CollectionTrait;

    public function __construct(int $code =0 , string $title = '', Author $author = null)
    {
        parent::__construct($code, $title, $author);
        $this->initDatabase();
    }

    public function save(): bool
    {	
        $insertOneResult = $this->database->books->insertOne([
            'code' => $this->database->books->countDocuments() + 1,
            'title' => $this->title,
            'author_code' => $this->author->code        
        ]);
        return $insertOneResult->getInsertedCount() == 1;    
    }

    public function update(): bool
    {
        $result = $this->database->books->updateOne(
            ['code' => $this->code],
            ['$set' => [
                'title' => $this->title,
                'author_code' => $this->author->code
            ]]
        );
        return $result->getModifiedCount() == 1;
    }

    public function delete(): bool
    {
        $result = $this->database->books->deleteOne(['code' => $this->code]);
        return $result->getDeletedCount() == 1;
    }
}