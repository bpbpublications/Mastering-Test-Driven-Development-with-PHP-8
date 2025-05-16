<?php
namespace Librarian\Controller;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
abstract class AbstractPageController
{
    public function run(string $action): void
    {
        $this->$action();
        $tokens = (explode('\\',get_class($this)));
        $controller = lcfirst(end($tokens));
        require __DIR__ . '/../../view/' . $controller . '/' . $action . '.phtml'; 
    }

    protected function redirect(string $controller, string $action, array $args = [])
    {
        $parameters = '';
        foreach($args as $arg => $value){
            $parameters .= "&$arg=$value";
        }
        header("Location: index.php?c=$controller&a=$action" . $parameters);
        exit;
    } 
}
