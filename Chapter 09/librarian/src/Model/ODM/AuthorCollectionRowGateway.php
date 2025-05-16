<?php
namespace Librarian\Model\ODM;
use Librarian\Model\Author;
use Librarian\Util\Config;
use Librarian\Model\RowGatewayInterface;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorCollectionRowGateway extends Author implements RowGatewayInterface
{
    use CollectionTrait;

    public function __construct(int $code = 0, string $firstName = '', string $middleName = '', string $lastName = '')
    {
        parent::__construct($code, $firstName, $middleName, $lastName);
        $this->initDatabase();
    }

    public function save(): bool
    {	
        $insertOneResult = $this->database->authors->insertOne([
            'code' => $this->database->authors->countDocuments() + 1,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'first_name' => $this->firstName
        ]);
        return $insertOneResult->getInsertedCount() == 1;
    }

    public function update(): bool
    {
        $result = $this->database->authors->updateOne(
            ['code' => $this->code],
            ['$set' => [
                'last_name' => $this->lastName,
                'middle_name' => $this->middleName,
                'first_name' => $this->firstName]
            ]
        );
        return $result->getModifiedCount() == 1;
    }

    public function delete(): bool
    {
        $result = $this->database->authors->deleteOne(['code' => $this->code]);
        return $result->getDeletedCount() == 1;
    }
}