<?php
namespace Librarian\Model;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
trait CodeTrait
{
    protected int $code;

    public function setCode(int $code)
    {
        $this->code = $code;
    }

    public function getCode(): int  
    {
        return $this->code;
    }
}
