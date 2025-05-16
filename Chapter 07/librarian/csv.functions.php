<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
// author
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

// book
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