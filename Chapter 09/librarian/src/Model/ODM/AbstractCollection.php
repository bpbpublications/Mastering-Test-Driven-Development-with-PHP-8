<?php
namespace Librarian\Model\ODM;
use Librarian\Util\Config;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
abstract class AbstractCollection
{
    use CollectionTrait;

    public function __construct()
    {
        $this->initDatabase();
    }
}