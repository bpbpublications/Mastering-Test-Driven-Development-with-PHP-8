<?php
namespace Librarian\Model\Filesystem;
use Librarian\Model\ORM\AbstractRowSet;
use Librarian\Model\Author;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorRowSet extends AbstractRowSet
{
    public function add(Author $row): void
    {
        $this->rows[] = $row;
    }
}
