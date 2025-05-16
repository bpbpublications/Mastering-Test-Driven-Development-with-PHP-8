<?php
namespace Librarian\Model\Filesystem;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
abstract class AbstractBookFilesystem extends AbstractAuthorFilesystem
{
    const ROW_LENGTH = 89;
    const TITLE_LENGTH = 80;
}
