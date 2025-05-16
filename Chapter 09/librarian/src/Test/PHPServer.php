<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
namespace Librarian\Test;

final class PHPServer 
{
    private $process;
    private static $instance = null;

    private function __construct()
    {}

    public static function getInstance()
    {
        if (self::$instance == null){
            self::$instance = new PHPServer();
        }
        return self::$instance;
    }

    public function start(): bool
    {
        $path = realpath(__DIR__ . '/../../');
        $descriptorspec = array(
            0 => ["pipe", "r"], 
            1 => ["pipe", "w"],
            2 => ["file", "/dev/null", "a"]
         );
        $process = proc_open('nohup php -S localhost:8008 router.php &',$descriptorspec,$path);
        sleep(1);
        if (is_bool($process)){
            return false;
        }
        $this->process = $process;
        return true;
    }

    public function stop(): bool
    {
        return proc_terminate($this->process);
    }

}
