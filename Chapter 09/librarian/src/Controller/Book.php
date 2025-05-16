<?php
namespace Librarian\Controller;
use Librarian\Model\AuthorRowSet;
use Librarian\Model\BookRowSet;
use Librarian\Model\Filesystem\BookPlainTextFinder;
use Librarian\Model\Filesystem\BookCSVFinder;
use Librarian\Model\Filesystem\BookJSONFinder;
use Librarian\Model\ORM\BookFinder;
use Librarian\Model\ODM\BookCollectionFinder;
use Librarian\Util\Config;
use Librarian\Model\FinderInterface;
use Librarian\Model\Book as BookModel;

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
class Book extends AbstractPageController
{
    protected BookModel $book;

    public function list(): void
    {
        
    }

    public function edit(): void
    {
        $code = $_GET['code'] ?? 0;
        $this->book = $this->getBookByCode($code);
    }

    public function save(): void
    {
        $code = (int) $_POST['code'] ?? 0;
        $title = $_POST['title'] ?? null;
        $authorCode = $_POST['author_code'] ?? null;
        if ($title == null){
            $message = 'No data, no recording';
        }
        if ($code == 0 && $this->insert($title,$authorCode)) {
            $message = 'Record saved successfully!';
        }
        if ($code <> 0 && $this->update($code,$title,$authorCode)) {
            $message = 'Record updated successfully!';
        }
        $this->redirect('book','message',['message' => base64_encode($message)]); 
    }

    public function message(): void
    {
        
    }

    protected function insert($title, $authorCode): bool
    {
        $storageFormat = Config::get('storage_format'); 
        $dataGateway = 'Librarian\Model\\' .  match($storageFormat){
            'txt' => 'Filesystem\BookPlainText',
            'csv' => 'Filesystem\BookCSV',
            'json' => 'Filesystem\BookJSON',
            'rdb' => 'ORM\BookTableGateway',
            'ddb' => 'ODM\BookCollection'                    
        };
        try {
            return (new $dataGateway())->save($title, $authorCode);
        } catch (\Exception $e){
            return false;
        }
    }
    
    protected function update($code, $title, $authorCode): bool
    {
        $storageFormat = Config::get('storage_format');
    
        $data = [
            'title' => $title,
            'author_code' => $authorCode,
        ];
        $dataGateway = 'Librarian\Model\\' .  match($storageFormat){
            'txt' => 'Filesystem\BookPlainText',
            'csv' => 'Filesystem\BookCSV',
            'json' => 'Filesystem\BookJSON',
            'rdb' => 'ORM\BookTableGateway',
            'ddb' => 'ODM\BookCollection'
        };
        try {
            return (new $dataGateway())->update($code, $data);
        } catch (\Exception $e){
            return false;
        }        
    }

    public function delete()
    {
        $code = $_GET['code'] ?? 0;
        $message = 'The record has not been deleted';
        if ($code == 0){
            $message = 'It cannot delete what does not exist';
        }
        if ($code <> 0 && $this->remove($code)) {
            $message = 'Record deleted successfully!';
        }
        $this->redirect('book','message',['message' => base64_encode($message)]);         
    }

    protected function remove(int $code)    
    {
        $storageFormat = Config::get('storage_format');
    
        $deleted = false;
        $dataGateway = 'Librarian\Model\\' .  match($storageFormat){
            'txt' => 'Filesystem\BookPlainText',
            'csv' => 'Filesystem\BookCSV',
            'json' => 'Filesystem\BookJSON',
            'rdb' => 'ORM\BookTableGateway',
            'ddb' => 'ODM\BookCollection'                    
        };

        try {
            return (new $dataGateway())->delete($code, [
                'last_name' => $lastName, 
                'middle_name' => $middleName, 
                'first_name' => $firstName]
            );
        } catch (\Exception $e){
            return false;
        }
    }

    protected function getBookByCode($code): BookModel
    {
        try {
            $author = $this->getBookFinder()->readByCode($code);
        } catch(\Exception $e) {
            $author = new AuthorModel();
        }
        return $author;
    }

    protected function listBooksInTable(): string
    {
        try {
            $books = $this->getBookFinder()->readAll();        
        } catch(\Exception $e) {
            $books = new BookRowSet();
        }
        $html = '';
        foreach($books as $book){
            $html.='<tr>';
            $html.='<td><a href="index.php?c=book&a=edit&code=' . $book->code . '">' . $book->code . '</a><td>';
            $html.="<td>{$book->title}<td>";
            $author = getAuthorByCode($book->author->code);
            $authorName = $author->firstName . ' ' . $author->middleName . ' ' . $author->lastName;
            $html.='<td>' . $authorName . '<td>';
            $html.='<td><a href="index.php?c=book&a=delete&code=' . $book->code . '">remove</a><td>';
            $html.='</tr>';
        }        
        return $html;
    }
    
    protected function getBookFinder(): FinderInterface
    {
        $storageFormat = Config::get('storage_format');
        switch($storageFormat){
            case 'txt':
                return new BookPlainTextFinder();
            case 'csv':
                return new BookCSVFinder();
            case 'json':
                return new BookJSONFinder();
            case 'rdb':
                return new BookFinder();
            case 'ddb':
                return new BookCollectionFinder();
        }
        throw new \Exception('invalid storage format');
    }    

    protected function listAuthorsForSelect($code)
    {
        try {
            $authors = getAuthorFinder()->readAll();        
        } catch(\Exception $e) {
            $authors = new AuthorRowSet();
        }
        $html = '';
        foreach($authors as $author){
            $authorName = $author->firstName . ' ' . $author->middleName . ' ' . $author->lastName;
            $html.='<option value="' . $author->code . '"' . ($author->code == $code ? ' selected ' : '') . '>' . $authorName . '</option>';
        }
        return $html;
    }    
}
