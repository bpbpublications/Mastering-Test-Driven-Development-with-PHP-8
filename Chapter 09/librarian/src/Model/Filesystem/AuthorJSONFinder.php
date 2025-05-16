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
class AuthorJSONFinder extends AbstractAuthorFilesystem implements FinderInterface
{
    public function readByCode(int $code): AbstractCode
    {
        $filepath = Config::get('author_json_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
        $content = file_get_contents($filepath);
        $authors = json_decode($content);
        $authors = is_null($authors) ? [] : $authors;
        foreach($authors as $author) {
            if ((int) $author->code == $code) {
                return new Author(
                    $author->code, 
                    $author->first_name,
                    $author->middle_name,
                    $author->last_name
                );
            }
        }
        return new Author();
    }
    
    public function readAll(): AbstractRowSet
    {
        $filepath = Config::get('author_json_filepath');
        if (!file_exists($filepath)){
            $handle = fopen($filepath,'w');
            fclose($handle);
        }        
        $content = file_get_contents($filepath);
        $records = json_decode($content);
        $records = is_null($records) ? [] : $records;
        $authors = new AuthorRowSet();
        foreach($records as $author) {
            $authors->add(new Author(
                $author->code, 
                $author->first_name,
                $author->middle_name,
                $author->last_name
            ));
        }
        return $authors;
    }
}
