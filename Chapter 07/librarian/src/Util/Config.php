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
        if (self::$config == null){
            self::$config = require __DIR__ . '/../../book.config.php';
        }        
        return self::$config[$key];
    }        
}