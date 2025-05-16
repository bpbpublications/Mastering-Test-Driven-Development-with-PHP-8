<?php
const AUTHOR_ROW_LENGTH = 65;
const BOOK_ROW_LENGTH = 89;

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
// author
function saveAuthorInPlainText($lastName, $middleName, $firstName)
{
    $filepath = getConfig()['author_plaintext_filepath'];
    $handle = fopen($filepath,'a+');
    fseek($handle, -AUTHOR_ROW_LENGTH, SEEK_END);
    $row = fread($handle, AUTHOR_ROW_LENGTH);
    $code = (int) substr($row, 0, 4);
    $code++;
    $row = formatField($code, 4) . formatField($lastName, 20) . 
    formatField($middleName, 20) . formatField($firstName, 20) . "\n";
    fwrite($handle,$row);
    fclose($handle);
    $handle = fopen($filepath,'r');
    $found = false;
    fseek($handle, -AUTHOR_ROW_LENGTH, SEEK_END);
    $currentRow = fread($handle, AUTHOR_ROW_LENGTH);
    $found = ($currentRow == $row);
    fclose($handle);
    return $found;
}

function saveAuthorInCSV($lastName, $middleName, $firstName)
{
    $filepath = getConfig()['author_csv_filepath'];
    $handle = fopen($filepath,'a+');
    $code = 0;
    while (!feof($handle)){
        $row = fgetcsv($handle, null, ';');
        $code = $row[0] ?? $code;
    }
    $code++;
    $fields = [
        formatField($code, 4),
        formatField($lastName, 20),
        formatField($middleName, 20),
        formatField($firstName, 20)
    ];
    fputcsv($handle, $fields, ';');
    fclose($handle);
    $handle = fopen($filepath,'r');
    $found = false;
    $currentCode = 0;
    while (!feof($handle)) {
        $currentRow = fgetcsv($handle, null, ';');
        $currentCode = $currentRow[0] ?? $currentCode;
    }
    $found = ((int) $currentCode == $code);  
    fclose($handle);
    return $found;
}

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

function readAuthorInPlainTextByCode(int $code)
{
    $filepath = getConfig()['author_plaintext_filepath'];
    $handle = fopen($filepath,'r');
    $author = [];
    while(!feof($handle)){
        $row = fread($handle, AUTHOR_ROW_LENGTH);
        $readCode = (int) substr($row,0,4);
        if ($readCode == $code){
            $author = [
                'code' => $code,
                'last_name' => trim(substr($row,4,20)),
                'middle_name' => trim(substr($row,25,20)),
                'first_name' => trim(substr($row,45,20))
            ];
            break;
        }    
    }
    fclose($handle);
    return $author;
}

function readAuthorInCSVByCode(int $code)
{
    $filepath = getConfig()['author_csv_filepath'];
    $handle = fopen($filepath,'r');
    $author = [];
    while(!feof($handle)){
        $row = fgetcsv($handle, null, ';');
        $readCode = (int) is_array($row) && isset($row[0]) ? $row[0] : 0;
        if ($readCode == $code){
            $author = [
                'code' => $code,
                'last_name' => trim($row[1]),
                'middle_name' => trim($row[2]),
                'first_name' => trim($row[3]),
            ];
            break;
        }    
    }
    fclose($handle);
    return $author;
}

function readAuthorInJSONByCode(int $code)
{
    $filepath = getConfig()['author_json_filepath'];
    $content = file_get_contents($filepath);
    $authors = json_decode($content);
    foreach($authors as $author) {
        if ((int) $author->code == $code) {
            return (array) $author;
        }
    }
    return [];
}

function readAuthorsInPlainText()
{
    $filepath = getConfig()['author_plaintext_filepath'];
    $handle = fopen($filepath,'r');
    $authors = [];
    while(!feof($handle)){
        $row = fread($handle, AUTHOR_ROW_LENGTH);
        $author = [
            'code' => (int) substr($row,0,4),
            'last_name' => trim(substr($row,4,20)),
            'middle_name' => trim(substr($row,25,20)),
            'first_name' => trim(substr($row,45,20))
        ];
        if ($author['code'] != 0) {
            $authors[] = $author;
        }
    }
    fclose($handle);
    return $authors;
}

function readAuthorsInCSV()
{
    $filepath = getConfig()['author_csv_filepath'];
    $handle = fopen($filepath,'r');
    $authors = [];
    while(!feof($handle)){
        $row = fgetcsv($handle, null, ';');
        if (!is_array($row) || count($row) != 4) continue;
        $author = [
            'code' => (int) $row[0],
            'last_name' => trim($row[1]),
            'middle_name' => trim($row[2]),
            'first_name' => trim($row[3]),
        ];
        $authors[] = $author;
    }
    fclose($handle);
    return $authors;
}

function readAuthorsInJSON()
{
    $filepath = getConfig()['author_json_filepath'];
    $content = file_get_contents($filepath);
    $authors = json_decode($content);
    foreach($authors as $index => $author) {
        $authors[$index] = (array) $author;
    }
    return $authors;
}

function updateAuthorInPlainText(int $code, array $data)
{
    $sourcePath = getConfig()['author_plaintext_filepath'];
    $targetPath = str_replace('.txt','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fread($sourceHandle, AUTHOR_ROW_LENGTH);
        $readCode = (int) substr($row,0,4);
        if ($readCode == $code){
            $author = [
                'code' => $code,
                'last_name' => trim(substr($row,4,20)),
                'middle_name' => trim(substr($row,25,20)),
                'first_name' => trim(substr($row,45,20))
            ];
            foreach($data as $key => $value){
                $author[$key] = $value;
            }
            $row = formatField($code,4) . formatField($author['last_name'],20) .
            formatField($author['middle_name'],20) . formatField($author['first_name'],20) . "\n";
            $changed = true;
        }
        fwrite($targetHandle,$row,AUTHOR_ROW_LENGTH);
    }
    fclose($sourceHandle);
    fclose($targetHandle);
    unlink($sourcePath);
    copy($targetPath,$sourcePath);
    unlink($targetPath);
    return $changed;
}

function updateAuthorInCSV(int $code, array $data)
{
    $sourcePath = getConfig()['author_csv_filepath'];
    $targetPath = str_replace('.csv','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fgetcsv($sourceHandle, null, ';');
        if (!is_array($row) || count($row) != 4) continue;
        $readCode = (int) $row[0];        
        if ($readCode == $code){
            $author = [
                'code' => $code,
                'last_name' => trim($row[1]),
                'middle_name' => trim($row[2]),
                'first_name' => trim($row[3]),
            ];
            foreach($data as $key => $value){
                $author[$key] = $value;
            }
            $row = [
                formatField($code,4),
                formatField($author['last_name'],4,20),
                formatField($author['middle_name'],20),
                formatField($author['first_name'],20)
            ];
            $changed = true;
        }
        fputcsv($targetHandle,$row,';');
    }
    fclose($sourceHandle);
    fclose($targetHandle);
    unlink($sourcePath);
    copy($targetPath,$sourcePath);
    unlink($targetPath);
    return $changed;
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

function deleteAuthorInPlainText(int $code)
{
    $sourcePath = getConfig()['author_plaintext_filepath'];
    $targetPath = str_replace('.txt','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fread($sourceHandle, AUTHOR_ROW_LENGTH);
        $readCode = (int) substr($row,0,4);
        if ($readCode == $code) {
            $changed = true;
            continue;
        }
        fwrite($targetHandle,$row,AUTHOR_ROW_LENGTH);
    }
    fclose($sourceHandle);
    fclose($targetHandle);
    unlink($sourcePath);
    copy($targetPath,$sourcePath);
    unlink($targetPath);
    return $changed;
}

function deleteAuthorInCSV(int $code)
{
    $sourcePath = getConfig()['author_csv_filepath'];
    $targetPath = str_replace('.csv','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fgetcsv($sourceHandle, null, ';');
        if (!is_array($row) || count($row) != 4) continue;
        $readCode = (int) $row[0];        
        if ($readCode == $code){
            $changed = true;
            continue;
        }
        fputcsv($targetHandle,$row,';');
    }
    fclose($sourceHandle);
    fclose($targetHandle);
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
    $authors = is_null($authors) ? [] : $authors;
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
function saveBookInPlainText(string $title, int $authorCode)
{
    $filepath = getConfig()['book_plaintext_filepath'];
    $handle = fopen($filepath,'a+');
    fseek($handle, -BOOK_ROW_LENGTH, SEEK_END);
    $row = fread($handle, BOOK_ROW_LENGTH);
    $code = (int) substr($row, 0, 4);
    $code++;
    $row = formatField($code, 4) . formatField($title, 80) . 
    formatField($authorCode, 4) . "\n";
    fwrite($handle,$row);
    fclose($handle);
    $handle = fopen($filepath,'r');
    $found = false;
    fseek($handle, -BOOK_ROW_LENGTH, SEEK_END);
    $currentRow = fread($handle, BOOK_ROW_LENGTH);
    $found = ($currentRow == $row);
    fclose($handle);
    return $found;
}

function saveBookInCSV(string $title, int $authorCode)
{
    $filepath = getConfig()['book_csv_filepath'];
    $handle = fopen($filepath,'a+');
    $code = 0;
    while (!feof($handle)){
        $row = fgetcsv($handle, null, ';');
        $code = $row[0] ?? $code;
    }
    $code++;
    $fields = [
        formatField($code, 4),
        formatField($title, 80),
        formatField($authorCode, 4)
    ];
    fputcsv($handle, $fields, ';');
    fclose($handle);
    $handle = fopen($filepath,'r');
    $found = false;
    $currentCode = 0;
    while (!feof($handle)) {
        $currentRow = fgetcsv($handle, null, ';');
        $currentCode = $currentRow[0] ?? $currentCode;
    }
    $found = ((int) $currentCode == $code);  
    fclose($handle);
    return $found;
}

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

function readBookInPlainTextByCode(int $code)
{
    $filepath = getConfig()['book_plaintext_filepath'];
    $handle = fopen($filepath,'r');
    $book = [];
    while(!feof($handle)){
        $row = fread($handle, BOOK_ROW_LENGTH);
        $readCode = (int) substr($row,0,4);
        if ($readCode == $code){
            $book = [
                'code' => $code,
                'title' => trim(substr($row,4,80)),
                'author_code' => trim(substr($row,84,4))
            ];
            break;
        }    
    }
    fclose($handle);
    return $book;
}

function readBookInCSVByCode(int $code)
{
    $filepath = getConfig()['book_csv_filepath'];
    $handle = fopen($filepath,'r');
    $book = [];
    while(!feof($handle)){
        $row = fgetcsv($handle, null, ';');
        $readCode = (int) is_array($row) && isset($row[0]) ? $row[0] : 0;
        if ($readCode == $code){
            $book = [
                'code' => $code,
                'title' => trim($row[1]),
                'author_code' => trim($row[2])
            ];
            break;
        }    
    }
    fclose($handle);
    return $book;
}

function readBookInJSONByCode(int $code)
{
    $filepath = getConfig()['book_json_filepath'];
    $content = file_get_contents($filepath);
    $books = json_decode($content);
    foreach($books as $book) {
        if ((int) $book->code == $code) {
            return (array) $book;
        }
    }
    return [];
}

function readBooksInPlainText()
{
    $filepath = getConfig()['book_plaintext_filepath'];
    $handle = fopen($filepath,'r');
    $books = [];
    while(!feof($handle)){
        $row = fread($handle, BOOK_ROW_LENGTH);
        $book = [
            'code' => (int) substr($row,0,4),
            'title' => trim(substr($row,4,80)),
            'author_code' => (int)(substr($row,84,4))
        ];
        if ($book['code'] != 0) {
            $books[] = $book;
        }
    }
    fclose($handle);
    return $books;
}

function readBooksInCSV()
{
    $filepath = getConfig()['book_csv_filepath'];
    $handle = fopen($filepath,'r');
    $books = [];
    while(!feof($handle)){
        $row = fgetcsv($handle, null, ';'); 
        if (!is_array($row) || count($row) != 3) continue;
        $book = [
            'code' => (int) $row[0],
            'title' => trim($row[1]),
            'author_code' => (int)$row[2]
        ];
        $books[] = $book;
    }
    fclose($handle);
    return $books;
}

function readBooksInJSON()
{
    $filepath = getConfig()['book_json_filepath'];
    $content = file_get_contents($filepath);
    $books = json_decode($content);
    foreach($books as $index => $book) {
        $books[$index] = (array) $book;
    }
    return $books;
}

function updateBookInPlainText(int $code, array $data)
{
    $sourcePath = getConfig()['book_plaintext_filepath'];
    $targetPath = str_replace('.txt','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fread($sourceHandle, BOOK_ROW_LENGTH);
        $readCode = (int) substr($row,0,4);
        if ($readCode == $code){
            $book = [
                'code' => $code,
                'title' => trim(substr($row,4,80)),
                'author_code' => trim(substr($row,84,4))
            ];
            foreach($data as $key => $value){
                $book[$key] = $value;
            }
            $row = formatField($code,4) . formatField($book['title'],80) .
            formatField($book['author_code'],4) . "\n";
            $changed = true;
        }
        fwrite($targetHandle,$row,BOOK_ROW_LENGTH);
    }
    fclose($sourceHandle);
    fclose($targetHandle);
    unlink($sourcePath);
    copy($targetPath,$sourcePath);
    unlink($targetPath);
    return $changed;
}

function updateBookInCSV(int $code, array $data)
{
    $sourcePath = getConfig()['book_csv_filepath'];
    $targetPath = str_replace('.csv','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fgetcsv($sourceHandle, null, ';');
        if (!is_array($row) || count($row) != 3) continue;
        $readCode = (int) $row[0];        
        if ($readCode == $code){
            $book = [
                'code' => $code,
                'title' => trim($row[1]),
                'author_code' => trim($row[2])
            ];
            foreach($data as $key => $value){
                $book[$key] = $value;
            }
            $row = [
                formatField($code,4),
                formatField($book['title'],4,80),
                formatField($book['author_code'],4)
            ];
            $changed = true;
        }
        fputcsv($targetHandle,$row,';');
    }
    fclose($sourceHandle);
    fclose($targetHandle);
    unlink($sourcePath);
    copy($targetPath,$sourcePath);
    unlink($targetPath);
    return $changed;
}

function updateBookInJSON(int $code, array $data)
{
    $sourcePath = getConfig()['book_json_filepath'];
    $content = file_get_contents($sourcePath);
    $books = json_decode($content);
    $books = is_null($books) ? [] : $books;
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

function deleteBookInPlainText(int $code)
{
    $sourcePath = getConfig()['book_plaintext_filepath'];
    $targetPath = str_replace('.txt','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fread($sourceHandle, BOOK_ROW_LENGTH);
        $readCode = (int) substr($row,0,4);
        if ($readCode == $code) {
            $changed = true;
            continue;
        }
        fwrite($targetHandle,$row,BOOK_ROW_LENGTH);
    }
    fclose($sourceHandle);
    fclose($targetHandle);
    unlink($sourcePath);
    copy($targetPath,$sourcePath);
    unlink($targetPath);
    return $changed;
}

function deleteBookInCSV(int $code)
{
    $sourcePath = getConfig()['book_csv_filepath'];
    $targetPath = str_replace('.csv','.tmp',$sourcePath);
    $sourceHandle = fopen($sourcePath,'r');
    $targetHandle = fopen($targetPath,'w');
    $changed = false;
    while(!feof($sourceHandle)){
        $row = fgetcsv($sourceHandle, null, ';');
        if (!is_array($row) || count($row) != 3) continue;
        $readCode = (int) $row[0];        
        if ($readCode == $code){
            $changed = true;
            continue;
        }
        fputcsv($targetHandle,$row,';');
    }
    fclose($sourceHandle);
    fclose($targetHandle);
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
    $books = is_null($books) ? [] : $books;
    $changed = false;
    foreach($books as $index => $book) {
        if ((int) $book->code == $code) {
            unset($books[$index]);
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

function getConfig()
{
    return require 'book.config.php';
}

function formatField($field, int $length)
{
    return str_pad($field, $length, ' ', STR_PAD_LEFT);
}

//author
function saveAuthor($lastName, $middleName, $firstName)
{
    $fileFormat = getConfig()['file_format'];

    $saved = false;
    switch($fileFormat){
        case 'txt':
            $saved = saveAuthorInPlainText($lastName, $middleName, $firstName);
            break;
        case 'csv':
            $saved = saveAuthorInCSV($lastName, $middleName, $firstName);
            break;
        case 'json':
            $saved = saveAuthorInJSON($lastName, $middleName, $firstName);
    }
    return $saved;
}

function listAuthorsInTable()
{
    $fileFormat = getConfig()['file_format'];
    $authors = [];
    switch($fileFormat){
        case 'txt':
            $authors = readAuthorsInPlainText();
            break;
        case 'csv':
            $authors = readAuthorsInCSV();
            break;
        case 'json':
            $authors = readAuthorsInJSON();
    }
    $html = '';
    foreach($authors as $author){
        $html.='<tr>';
        $html.='<td><a href="author.edit.php?code=' . $author['code'] . '">' . $author['code'] . '</a><td>';
        $html.="<td>{$author['first_name']} {$author['middle_name']} {$author['last_name']}<td>";
        $html.='<td><a href="author.delete.php?code=' . $author['code'] . '">remove</a><td>';
        $html.='</tr>';
    }
    return $html;
}

function listBooksForSelect($code)
{
    $fileFormat = getConfig()['file_format'];
    $authors = [];
    switch($fileFormat){
        case 'txt':
            $authors = readAuthorsInPlainText();
            break;
        case 'csv':
            $authors = readAuthorsInCSV();
            break;
        case 'json':
            $authors = readAuthorsInJSON();
    }
    $html = '';
    foreach($authors as $author){
        $authorName = $author['first_name'] . ' ' . $author['middle_name'] . ' ' . $author['last_name'];
        $html.='<option value="' . $author['code'] . '"' . ($author['code'] == $code ? ' selected ' : '') . '>' . $authorName . '</option>';
    }
    return $html;
}

function getAuthorByCode($code)
{
    $fileFormat = getConfig()['file_format'];

    $author = [];
    switch($fileFormat){
        case 'txt':
            $author = readAuthorInPlainTextByCode($code);
            break;
        case 'csv':
            $author = readAuthorInCSVByCode($code);
            break;
        case 'json':
            $author = readAuthorInJSONByCode($code);
    }
    if (empty($author)){
        $author = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => ''
        ];
    }
    return $author;
}

function updateAuthor($code, $lastName, $middleName, $firstName)
{
    $fileFormat = getConfig()['file_format'];

    $data = [
        'last_name' => $lastName,
        'middle_name' => $middleName,
        'first_name' => $firstName
    ];

    $saved = false;
    switch($fileFormat){
        case 'txt':
            $saved = updateAuthorInPlainText($code, $data);
            break;
        case 'csv':
            $saved = updateAuthorInCSV($code, $data);
            break;
        case 'json':
            $saved = updateAuthorInJSON($code, $data);
    }
    return $saved;
}

function deleteAuthor($code)
{
    $fileFormat = getConfig()['file_format'];

    $deleted = false;
    switch($fileFormat){
        case 'txt':
            $deleted = deleteAuthorInPlainText($code);
            break;
        case 'csv':
            $deleted = deleteAuthorInCSV($code);
            break;
        case 'json':
            $deleted = deleteAuthorInJSON($code);
    }
    return $deleted;
}

//book
function saveBook($title, $authorCode)
{
    $fileFormat = getConfig()['file_format'];

    $saved = false;
    switch($fileFormat){
        case 'txt':
            $saved = saveBookInPlainText($title, $authorCode);
            break;
        case 'csv':
            $saved = saveBookInCSV($title, $authorCode);
            break;
        case 'json':
            $saved = saveBookInJSON($title, $authorCode);
    }
    return $saved;
}

function listBooksInTable()
{
    $fileFormat = getConfig()['file_format'];
    $books = [];
    switch($fileFormat){
        case 'txt':
            $books = readBooksInPlainText();
            break;
        case 'csv':
            $books = readBooksInCSV();
            break;
        case 'json':
            $books = readBooksInJSON();
    }
    $html = '';
    foreach($books as $book){
        $html.='<tr>';
        $html.='<td><a href="book.edit.php?code=' . $book['code'] . '">' . $book['code'] . '</a><td>';
        $html.="<td>{$book['title']}<td>";
        $author = getAuthorByCode($book['author_code']);
        $authorName = $author['first_name'] . ' ' . $author['middle_name'] . ' ' . $author['last_name'];
        $html.='<td>' . $authorName . '<td>';
        $html.='<td><a href="book.delete.php?code=' . $book['code'] . '">remove</a><td>';
        $html.='</tr>';
    }
    return $html;
}

function getBookByCode($code)
{
    $fileFormat = getConfig()['file_format'];

    $book = [];
    switch($fileFormat){
        case 'txt':
            $book = readBookInPlainTextByCode($code);
            break;
        case 'csv':
            $book = readBookInCSVByCode($code);
            break;
        case 'json':
            $book = readBookInJSONByCode($code);
    }
    if (empty($book)){
        $book = [
            'title' => '',
            'author_code' => 0
        ];
    }
    return $book;
}

function updateBook($code, $title, $authorCode)
{
    $fileFormat = getConfig()['file_format'];

    $data = [
        'title' => $title,
        'author_code' => $authorCode
    ];

    $saved = false;
    switch($fileFormat){
        case 'txt':
            $saved = updateBookInPlainText($code, $data);
            break;
        case 'csv':
            $saved = updateBookInCSV($code, $data);
            break;
        case 'json':
            $saved = updateBookInJSON($code, $data);
    }
    return $saved;
}

function deleteBook($code)
{
    $fileFormat = getConfig()['file_format'];

    $deleted = false;
    switch($fileFormat){
        case 'txt':
            $deleted = deleteBookInPlainText($code);
            break;
        case 'csv':
            $deleted = deleteBookInCSV($code);
            break;
        case 'json':
            $deleted = deleteBookInJSON($code);
    }
    return $deleted;
}



function getPathForFile(string $entity)
{
    $fileFormat = getConfig()['file_format'];

    $path = '';
    switch($fileFormat){
        case 'txt':
            $path = getConfig()[$entity . '_plaintext_filepath'];
            break;
        case 'csv':
            $path = getConfig()[$entity . '_csv_filepath'];
            break;
        case 'json':
            $path = getConfig()[$entity . '_json_filepath'];
    }
    return $path;
}

function prepareFile(string $entity)
{
    $path = getPathForFile($entity);
    if (!file_exists($path)){
        $handle = fopen($path,'w');
        fclose($handle);
    }
}

