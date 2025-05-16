<?php
namespace Librarian\Model;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class Book extends AbstractCode
{
    public int $code;
    public string $title;
    public ?Author $author;
    
    public function __construct(int $code = 0, string $title = '', Author $author = null)
    {
        $this->code = $code;
    	$this->title = $title;
    	$this->author = $author;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    public function getTitle()
    {
        return $this->title;        
    }

    public function getAuthor()
    {
        return $this->author;        
    }   
}
