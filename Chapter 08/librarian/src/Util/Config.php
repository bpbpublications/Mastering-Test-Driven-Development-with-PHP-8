<?php
namespace Librarian\Util;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class Config
{
    private static ?array $config = null;

    public static function get(string $key)
    {
        $configFileName = 'book.config.php';
        if ((defined('LIBRARIAN_TEST_ENVIRONMENT') && LIBRARIAN_TEST_ENVIRONMENT) ||
        getenv('LIBRARIAN_TEST_ENVIRONMENT')){
            $configFileName = 'book.config.test.php';
        }
        if (self::$config == null){
            self::$config = require __DIR__ . '/../../' . $configFileName;
        }        
        return self::$config[$key];
    }        
}
