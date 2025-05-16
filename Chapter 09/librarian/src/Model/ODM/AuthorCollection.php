<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorCollection extends AbstractCollection
{
    public function save($lastName, $middleName, $firstName)
    {	
        $insertOneResult = $this->database->authors->insertOne([
            'code' => $this->database->authors->countDocuments() + 1,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'first_name' => $firstName
        ]);
        return $insertOneResult->getInsertedCount() == 1;
    }

    public function readByCode(int $code)
    {
        $result = $this->database->authors->findOne(['code' => $code]);
        if (is_null($result)){
            return [];
        }
        return $result;
    }

    public function readAll()
    {
        $result = $this->database->authors->find();
        if (is_null($result)){
            return [];
        }
        return $result->toArray();
    }

    public function update(int $code, array $data)
    {
        $result = $this->database->authors->updateOne(
            ['code' => $code],
            ['$set' => $data]
        );
        return $result->getModifiedCount() == 1;
    }

    public function delete(int $code)
    {
        $result = $this->database->authors->deleteOne(['code' => $code]);
        return $result->getDeletedCount() == 1;
    }
}