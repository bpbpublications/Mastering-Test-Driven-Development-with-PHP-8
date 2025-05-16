<?php
namespace Librarian\Model;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class Author extends AbstractCode
{
    public string $firstName;
    public string $middleName;
    public string $lastName;

    public function __construct(int $code = 0, string $firstName = '', string $middleName = '', string $lastName = '')
    {
        $this->code = $code;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }
}
