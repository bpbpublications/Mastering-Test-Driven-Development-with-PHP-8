<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorJSON extends AbstractAuthorFilesystem
{
    public function save($lastName, $middleName, $firstName): bool
    {
        $filepath = Config::get('author_json_filepath');
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
            'last_name'  => $lastName,
            'middle_name' => $middleName,
            'first_name' =>  $firstName
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
        $filepath = Config::get('author_json_filepath');
        $content = file_get_contents($filepath);
        $authors = json_decode($content);
        $authors = is_null($authors) ? [] : $authors;
        foreach($authors as $author) {
            if ((int) $author->code == $code) {
                return (array) $author;
            }
        }
        return [];
    }
    
    public function readAll(): array
    {
        $filepath = Config::get('author_json_filepath');
        $content = file_get_contents($filepath);
        $authors = json_decode($content);
        $authors = is_null($authors) ? [] : $authors;
        foreach($authors as $index => $author) {
            $authors[$index] = (array) $author;
        }
        return $authors;
    }

    public function update(int $code, array $data): bool
    {
        $sourcePath = Config::get('author_json_filepath');
        $content = file_get_contents($sourcePath);
        $authors = json_decode($content);
        $authors = is_null($authors) ? [] : $authors;
        $changed = false;
        foreach($authors as $index => $author) {
            if ((int) $author->code == $code) {
                foreach($data as $key => $value){
                    $author->$key = $value;
                }
                $authors[$index] = $author;
                $changed = true;
            }
        }
        $targetPath = str_replace('.json','.tmp',$sourcePath);
        file_put_contents($targetPath,json_encode($authors));
        unlink($sourcePath);
        copy($targetPath,$sourcePath);
        unlink($targetPath);  
        return $changed;
    }

    public function delete(int $code): bool
    {
        $sourcePath = Config::get('author_json_filepath');
        $content = file_get_contents($sourcePath);
        $authors = json_decode($content);
        $changed = false;
        foreach($authors as $index => $author) {
            if ((int) $author->code == $code) {
                unset($authors[$index]);
                $changed = true;
            }
        }
        $targetPath = str_replace('.json','.tmp',$sourcePath);
        file_put_contents($targetPath,json_encode($authors));
        unlink($sourcePath);
        copy($targetPath,$sourcePath);
        unlink($targetPath);  
        return $changed;
    }
}
