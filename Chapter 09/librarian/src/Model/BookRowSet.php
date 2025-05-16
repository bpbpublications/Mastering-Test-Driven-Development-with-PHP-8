<?php
namespace Librarian\Model;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class BookRowSet extends AbstractRowSet
{
    public function __construct(array $rows = [])
    {
        foreach($rows as $row)
        {
            $this->rows[] = new Book((int)$row['code'], $row['title'], new Author($row['author_code']));
        }
    }
}

