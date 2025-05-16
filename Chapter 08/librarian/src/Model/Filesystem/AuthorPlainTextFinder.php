<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
use Librarian\Model\Author;

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorPlainTextFinder extends AbstractAuthorFilesystem
{
    public function readByCode(int $code): Author
    {
        $filepath = Config::get('author_plaintext_filepath');
        $handle = fopen($filepath,'r');
        $author = new Author();
        while(!feof($handle)){
            $row = fread($handle, self::ROW_LENGTH);
            $readCode = (int) substr($row,0,self::CODE_LENGTH);
            if ($readCode == $code){
                $author = new Author(
                    $code,
                    trim(substr($row, self::CODE_LENGTH + (2 * self::NAME_LENGTH) + 1, self::NAME_LENGTH)),
                    trim(substr($row, self::CODE_LENGTH + self::NAME_LENGTH + 1, self::NAME_LENGTH)),                    
                    trim(substr($row, self::CODE_LENGTH, self::NAME_LENGTH))
                );
                break;
            }    
        }
        fclose($handle);
        return $author;
    }

    public function readAll(): AuthorRowSet
    {
        $filepath = getConfig()['author_plaintext_filepath'];
        $handle = fopen($filepath,'r');
        $authors = new AuthorRowSet();
        while(!feof($handle)){
            $row = fread($handle, AUTHOR_ROW_LENGTH);
            $author = new Author(
                (int) trim(substr($row, 0, self::CODE_LENGTH)),
                trim(substr($row, self::CODE_LENGTH + (2 * self::NAME_LENGTH) + 1, self::NAME_LENGTH)),
                trim(substr($row, self::CODE_LENGTH + self::NAME_LENGTH + 1, self::NAME_LENGTH)),                    
                trim(substr($row, self::CODE_LENGTH, self::NAME_LENGTH))
            );
            if ((int)$author->code != 0) {
                $authors->add($author);
            }
        }
        fclose($handle);
        return $authors;
    }
}
