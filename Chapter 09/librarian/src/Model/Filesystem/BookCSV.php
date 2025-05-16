<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookCSV extends AbstractBookFilesystem
{
    public function save(string $title, int $authorCode): bool
    {
        $filepath = Config::get('book_csv_filepath');
        $handle = fopen($filepath,'a+');
        $code = 0;
        while (!feof($handle)){
            $row = fgetcsv($handle, null, ';');
            $code = $row[0] ?? $code;
        }
        $code++;
        $fields = [
            $this->formatField($code, 4),
            $this->formatField($title, 80),
            $this->formatField($authorCode, 4)
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
    
    public function readByCode(int $code): array
    {
        $filepath = Config::get('book_csv_filepath');
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
    
    public function readAll(): array
    {
        $filepath = Config::get('book_csv_filepath');
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
    
    public function update(int $code, array $data): bool
    {
        $sourcePath = Config::get('book_csv_filepath');
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
                    $this->formatField($code,self::CODE_LENGTH),
                    $this->formatField($book['title'],self::TITLE_LENGTH),
                    $this->formatField($book['author_code'],self::CODE_LENGTH)
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
    
    public function delete(int $code): bool
    {
        $sourcePath = Config::get('book_csv_filepath');
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
}