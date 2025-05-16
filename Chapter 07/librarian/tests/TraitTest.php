<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Librarian\Model\TraitUser;

/**
 * @covers Author
 * Change to CoversTrait from PHPUnit 12
*/
#[CoversClass(TraitUser::class)]
class TraitTest extends TestCase
{
    public function testTrait()
    {
        $traitUser = new TraitUser();
        $traitUser->setCode(42);

        $this->assertEquals(42, $traitUser->getCode());
    }
}
