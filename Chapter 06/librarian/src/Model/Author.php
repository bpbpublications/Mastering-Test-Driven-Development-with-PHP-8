<?php
namespace Librarian\Model;
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class Author extends AbstractCode
{
    private string $firstName;
    private string $middleName;
    private string $lastName;

    public function __construct(string $firstName = '', string $middleName = '', string $lastName = '')
    {
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }

    public function __destruct()
    {
        $objectId = spl_object_id($this);
        syslog(LOG_DEBUG,"object $objectId was destroyed");
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }    

    public function __toString()
    {
        return "{$this->firstName} {$this->middleName} {$this->lastName}"; 
    }

    public function __invoke(string $firstName = '', string $middleName = '', string $lastName = '')
    {
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;        
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    public function setMiddleName(string $middleName)
    {
        $this->middleName = $middleName;
    }
    
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    public function getFirstName()
    {
        return $this->firstName;        
    }

    public function getMiddleName()
    {
        return $this->middleName;        
    }

    public function getLastName()
    {
        return $this->lastName;
    }

}
