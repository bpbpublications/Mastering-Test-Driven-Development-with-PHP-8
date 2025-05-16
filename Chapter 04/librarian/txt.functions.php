<?php
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
