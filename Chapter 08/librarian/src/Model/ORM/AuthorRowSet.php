<?php
namespace Librarian\Model\ORM;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class AuthorRowSet extends AbstractRowSet
{
    public function add(AuthorRowGateway $row): void
    {
        $this->rows[] = $row;
    }
}
