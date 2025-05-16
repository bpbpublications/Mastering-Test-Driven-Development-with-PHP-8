<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
namespace Librarian\Test;

use PHPUnit\Framework\TestCase;

abstract class AbstractBackupTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        
        $bookPath = getPathForFile('book');
        $authorPath = getPathForFile('author');
        if (file_exists($bookPath)){
            copy($bookPath,$bookPath . '.bkp');
            unlink($bookPath);
        }
        if (file_exists($authorPath)){
            copy($authorPath,$authorPath . '.bkp');
            unlink($authorPath);
        }
    }
    
    public static function tearDownAfterClass(): void
    {
        
        $bookPath = getPathForFile('book');
        $authorPath = getPathForFile('author');
        if (file_exists($bookPath . 'bkp')){
            copy($bookPath . '.bkp', $bookPath);
            unlink($bookPath . '.bkp');
        }
        if (file_exists($authorPath . '.bkp')){
            copy($authorPath . '.bkp', $authorPath);
            unlink($authorPath . '.bkp');
        }
    }
    
    protected function deleteBooksAndAuthors(string $type)
    {
        $type = ($type == 'txt' ? 'plaintext' : $type);
        $filepath = getConfig()["book_{$type}_filepath"];
        unlink($filepath);
        $filepath = getConfig()["author_{$type}_filepath"];
        unlink($filepath);
    }    
}
