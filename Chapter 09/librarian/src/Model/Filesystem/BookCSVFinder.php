<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
use Librarian\Model\Book;
use Librarian\Model\Author;
use Librarian\Model\BookRowSet;
use Librarian\Model\FinderInterface;
use Librarian\Model\AbstractCode;
use Librarian\Model\AbstractRowSet;

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookCSVFinder extends AbstractBookFilesystem implements FinderInterface
{  
    public function readByCode(int $code): AbstractCode
    {
        $filepath = Config::get('book_csv_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
        $handle = fopen($filepath,'r');
        $book = new Book();
        while(!feof($handle)){
            $row = fgetcsv($handle, null, ';');
            $readCode = (int) is_array($row) && isset($row[0]) ? $row[0] : 0;
            if ($readCode == $code){
                $book = new Book(
                    $code,
                    trim($row[1]),
                    new Author((int)trim($row[2]))
                );
                break;
            }    
        }
        fclose($handle);
        return $book;
    }
    
    public function readAll(): AbstractRowSet
    {
        $filepath = Config::get('book_csv_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
        $handle = fopen($filepath,'r');
        $books = new BookRowSet();
        while(!feof($handle)){
            $row = fgetcsv($handle, null, ';'); 
            if (!is_array($row) || count($row) != 3) continue;
            $book = new Book(
                (int) $row[0],
                trim($row[1]),
                new Author((int)$row[2])
            );
            $books->add($book);
        }
        fclose($handle);
        return $books;
    }
}