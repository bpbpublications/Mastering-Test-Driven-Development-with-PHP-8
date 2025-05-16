<?php
namespace Librarian\Model\Filesystem;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
abstract class AbstractAuthorFilesystem
{
    const ROW_LENGTH = 65;
    const CODE_LENGTH = 4;
    const NAME_LENGTH = 20;        

    public function formatField($field, int $length)
    {
        return str_pad($field, $length, ' ', STR_PAD_LEFT);
    }   
}
