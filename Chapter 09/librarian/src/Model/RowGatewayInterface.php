<?php
namespace Librarian\Model;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
interface RowGatewayInterface
{
    public function save(): bool;
    public function update(): bool;
    public function delete(): bool;
}
