<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
use Librarian\Model\Author;
use Librarian\Model\AuthorRowSet;
use Librarian\Model\FinderInterface;
use Librarian\Model\AbstractCode;
use Librarian\Model\AbstractRowSet;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorCollectionFinder extends AbstractCollection implements FinderInterface
{
    public function readByCode(int $code): AbstractCode
    {
        $result = $this->database->authors->findOne(['code' => $code]);
        if (is_null($result)){
            return new Author();
        }
        return new Author($code, $result['first_name'],$result['middle_name'],$result['last_name']);
    }

    public function readAll(): AbstractRowSet
    {
        $result = $this->database->authors->find();
        if (is_null($result)){
            return new AuthorRowSet();
        }
        return new AuthorRowSet($result->toArray());
    }
}