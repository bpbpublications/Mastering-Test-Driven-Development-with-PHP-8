<?php
namespace Librarian\Model\Filesystem;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorPlainText extends AbstractAuthorFilesystem
{
    public function save($lastName, $middleName, $firstName): bool
    {
        $filepath = Config::get('author_plaintext_filepath');
        $handle = fopen($filepath,'a+');
        fseek($handle, -self::ROW_LENGTH, SEEK_END);
        $row = fread($handle, self::ROW_LENGTH);
        $code = (int) substr($row, 0, self::CODE_LENGTH);
        $code++;
        $row = $this->formatField($code, self::CODE_LENGTH) . $this->formatField($lastName, self::NAME_LENGTH) . 
        $this->formatField($middleName, self::NAME_LENGTH) . $this->formatField($firstName, self::NAME_LENGTH) . "\n";
        fwrite($handle,$row);
        fclose($handle);
        $handle = fopen($filepath,'r');
        $found = false;
        fseek($handle, -self::ROW_LENGTH, SEEK_END);
        $currentRow = fread($handle, self::ROW_LENGTH);
        $found = ($currentRow == $row);
        fclose($handle);
        return $found;
    }

    public function readByCode(int $code): array
    {
        $filepath = Config::get('author_plaintext_filepath');
        $handle = fopen($filepath,'r');
        $author = [];
        while(!feof($handle)){
            $row = fread($handle, self::ROW_LENGTH);
            $readCode = (int) substr($row,0,self::CODE_LENGTH);
            if ($readCode == $code){
                $author = [
                    'code' => $code,
                    'last_name' => trim(substr($row, self::CODE_LENGTH, self::NAME_LENGTH)),
                    'middle_name' => trim(substr($row, self::CODE_LENGTH + self::NAME_LENGTH + 1, self::NAME_LENGTH)),
                    'first_name' => trim(substr($row, self::CODE_LENGTH + (2 * self::NAME_LENGTH) + 1, self::NAME_LENGTH))
                ];
                break;
            }    
        }
        fclose($handle);
        return $author;
    }

    public function readAll(): array
    {
        $filepath = getConfig()['author_plaintext_filepath'];
        $handle = fopen($filepath,'r');
        $authors = [];
        while(!feof($handle)){
            $row = fread($handle, AUTHOR_ROW_LENGTH);
            $author = [
                'code' => (int) substr($row,0,self::CODE_LENGTH),
                'last_name' => trim(substr($row, self::CODE_LENGTH, self::NAME_LENGTH)),
                'middle_name' => trim(substr($row, self::CODE_LENGTH + self::NAME_LENGTH + 1, self::NAME_LENGTH)),
                'first_name' => trim(substr($row, self::CODE_LENGTH + (2 * self::NAME_LENGTH) + 1, self::NAME_LENGTH))
            ];
            if ($author['code'] != 0) {
                $authors[] = $author;
            }
        }
        fclose($handle);
        return $authors;
    }
    
    public function update(int $code, array $data): bool
    {
        $sourcePath = Config::get('author_plaintext_filepath');
        $targetPath = str_replace('.txt','.tmp',$sourcePath);
        $sourceHandle = fopen($sourcePath,'r');
        $targetHandle = fopen($targetPath,'w');
        $changed = false;
        while(!feof($sourceHandle)){
            $row = fread($sourceHandle, self::ROW_LENGTH);
            $readCode = (int) substr($row,0,self::CODE_LENGTH);
            if ($readCode == $code){
                $author = [
                    'code' => $code,
                    'last_name' => trim(substr($row, self::CODE_LENGTH, self::NAME_LENGTH)),
                    'middle_name' => trim(substr($row, self::CODE_LENGTH + self::NAME_LENGTH + 1, self::NAME_LENGTH)),
                    'first_name' => trim(substr($row, self::CODE_LENGTH + (2 * self::NAME_LENGTH) + 1, self::NAME_LENGTH))
                ];
                foreach($data as $key => $value){
                    $author[$key] = $value;
                }
                $row = $this->formatField($code,self::CODE_LENGTH) . $this->formatField($author['last_name'], self::NAME_LENGTH) .
                $this->formatField($author['middle_name'], self::NAME_LENGTH) . $this->formatField($author['first_name'], self::NAME_LENGTH) . "\n";
                $changed = true;
            }
            fwrite($targetHandle,$row,self::ROW_LENGTH);
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
        $sourcePath = Config::get('author_plaintext_filepath');
        $targetPath = str_replace('.txt','.tmp',$sourcePath);
        $sourceHandle = fopen($sourcePath,'r');
        $targetHandle = fopen($targetPath,'w');
        $changed = false;
        while(!feof($sourceHandle)){
            $row = fread($sourceHandle, self::ROW_LENGTH);
            $readCode = (int) substr($row,0,4);
            if ($readCode == $code) {
                $changed = true;
                continue;
            }
            fwrite($targetHandle,$row,self::ROW_LENGTH);
        }
        fclose($sourceHandle);
        fclose($targetHandle);
        unlink($sourcePath);
        copy($targetPath,$sourcePath);
        unlink($targetPath);
        return $changed;
    }    
}
