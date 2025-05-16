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
class AuthorCSVFinder extends AbstractAuthorFilesystem implements FinderInterface
{
    public function readByCode(int $code): AbstractCode
    {
        $filepath = Config::get('author_csv_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
        $handle = fopen($filepath,'r');
        $author = new Author();
        while(!feof($handle)){
            $row = fgetcsv($handle, null, ';');
            $readCode = (int) is_array($row) && isset($row[0]) ? $row[0] : 0;
            if ($readCode == $code){
                $author = new Author(
                    $code,
                    trim($row[3]),
                    trim($row[2]),
                    trim($row[1])
                );
                break;
            }
        }
        fclose($handle);
        return $author;
    }

    public function readAll(): AbstractRowSet
    {
        $filepath = Config::get('author_csv_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
        $handle = fopen($filepath,'r');
        $authors = new AuthorRowSet();
        while(!feof($handle)){
            $row = fgetcsv($handle, null, ';');
            if (!is_array($row) || count($row) != self::CODE_LENGTH) continue;
            $author = new Author(
                (int) $row[0],
                trim($row[3]),
                trim($row[2]),
                trim($row[1])
            );
            $authors->add($author);
        }
        fclose($handle);
        return $authors;
    }
}
