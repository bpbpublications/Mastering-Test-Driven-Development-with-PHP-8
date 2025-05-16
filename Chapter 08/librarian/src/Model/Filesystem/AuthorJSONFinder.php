<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
use Librarian\Model\Author;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorJSONFinder extends AbstractAuthorFilesystem
{
    public function readByCode(int $code): Author
    {
        $filepath = Config::get('author_json_filepath');
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
    
    public function readAll(): AuthorRowSet
    {
        $filepath = Config::get('author_json_filepath');
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
