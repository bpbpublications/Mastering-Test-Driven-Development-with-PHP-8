<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookJSON extends AbstractBookFilesystem
{
    public function save(string $title, int $authorCode): bool
    {
        $filepath = Config::get('book_json_filepath');
        $handle = fopen($filepath,'a');
        fclose($handle);
        $json = json_decode(file_get_contents($filepath));
        if ($json == NULL){
            $json = [];
        }
        $code = 0;
        foreach ($json as $row) {
            $code = $row->code;
        }
        $code++;
        $dict = [ 
            'code' => $code,
            'title'  => $title,
            'author_code' => $authorCode,
        ];
        $json[] = $dict;
        $text = json_encode($json);
        file_put_contents($filepath, $text);
        $json = json_decode(file_get_contents($filepath));
        $found = false;
        $currentCode = 0;
        foreach ($json as $row) {
            $currentCode = $row->code;
        }
        $found = ($currentCode == $code);
        return $found;
    }

    public function readByCode(int $code): array
    {
        $filepath = Config::get('book_json_filepath');
        $content = file_get_contents($filepath);
        $books = json_decode($content);
        $books = is_null($books) ? [] : $books;
        foreach($books as $book) {
            if ((int) $book->code == $code) {
                return (array) $book;
            }
        }
        return [];
    }

    function readAll(): array
    {
        $filepath = getConfig()['book_json_filepath'];
        $content = file_get_contents($filepath);
        $books = json_decode($content);
        $books = is_null($books) ? [] : $books;
        foreach($books as $index => $book) {
            $books[$index] = (array) $book;
        }
        return $books;
    }

    public function update(int $code, array $data): bool
    {
        $sourcePath = Config::get('book_json_filepath');
        $content = file_get_contents($sourcePath);
        $books = json_decode($content);
        $changed = false;
        foreach($books as $index => $book) {
            if ((int) $book->code == $code) {
                foreach($data as $key => $value){
                    $book->$key = $value;
                }
                $books[$index] = $book;
                $changed = true;
            }
        }
        $targetPath = str_replace('.json','.tmp',$sourcePath);
        file_put_contents($targetPath,json_encode($books));
        unlink($sourcePath);
        copy($targetPath,$sourcePath);
        unlink($targetPath);  
        return $changed;
    }
    
    public function delete(int $code): bool
    {
        $sourcePath = Config::get('book_json_filepath');
        $content = file_get_contents($sourcePath);
        $books = json_decode($content);
        $changed = false;
        foreach($books as $index => $book) {
            if ((int) $book->code == $code) {
                unset($books[$index]);
            }
            $changed = true;
        }
        $targetPath = str_replace('.json','.tmp',$sourcePath);
        file_put_contents($targetPath,json_encode($books));
        unlink($sourcePath);
        copy($targetPath,$sourcePath);
        unlink($targetPath);  
        return $changed;
    }    
}