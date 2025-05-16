<?php
namespace Librarian\Model\Filesystem;
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
class AuthorPlainTextFinder extends AbstractAuthorFilesystem implements FinderInterface
{
    public function readByCode(int $code): AbstractCode
    {
        $filepath = Config::get('author_plaintext_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }
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

    public function readAll(): AbstractRowSet
    {
        $filepath = Config::get('author_plaintext_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
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
