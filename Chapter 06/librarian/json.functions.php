<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
// author
function saveAuthorInJSON($lastName, $middleName, $firstName)
{
    $filepath = getConfig()['author_json_filepath'];
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

function readAuthorInJSONByCode(int $code)
{
    $filepath = getConfig()['author_json_filepath'];
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

function readAuthorsInJSON()
{
    $filepath = getConfig()['author_json_filepath'];
    $content = file_get_contents($filepath);
    $authors = json_decode($content);
    $authors = is_null($authors) ? [] : $authors;
    foreach($authors as $index => $author) {
        $authors[$index] = (array) $author;
    }
    return $authors;
}

function updateAuthorInJSON(int $code, array $data)
{
    $sourcePath = getConfig()['author_json_filepath'];
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

function deleteAuthorInJSON(int $code)
{
    $sourcePath = getConfig()['author_json_filepath'];
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
// book
function saveBookInJSON(string $title, int $authorCode)
{
    $filepath = getConfig()['book_json_filepath'];
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

function readBookInJSONByCode(int $code)
{
    $filepath = getConfig()['book_json_filepath'];
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

function readBooksInJSON()
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

function updateBookInJSON(int $code, array $data)
{
    $sourcePath = getConfig()['book_json_filepath'];
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

function deleteBookInJSON(int $code)
{
    $sourcePath = getConfig()['book_json_filepath'];
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