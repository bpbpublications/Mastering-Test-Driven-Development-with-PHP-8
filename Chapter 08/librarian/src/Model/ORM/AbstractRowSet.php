<?php
namespace Librarian\Model\ORM;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
abstract class AbstractRowSet implements \Iterator
{
    protected array $rows;
    protected int $key = 0;

    public function current(): mixed
    {
        return $this->rows[$this->key];
    }

    public function key(): mixed
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->key++;
    }

    public function rewind(): void
    {
        $this->key = 0;
    }

    public function valid(): bool
    {
        return isset($this->rows[$this->key]);
    }

    public function get(int $key): mixed
    {
        return $this->rows[$key];
    }
}
