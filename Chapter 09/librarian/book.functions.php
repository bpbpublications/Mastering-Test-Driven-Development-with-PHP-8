<?php
use Librarian\Model\Author;
use Librarian\Model\AuthorRowSet;
use Librarian\Model\Filesystem\AuthorPlainTextFinder;
use Librarian\Model\Filesystem\AuthorCSVFinder;
use Librarian\Model\Filesystem\AuthorJSONFinder;
use Librarian\Model\ORM\AuthorFinder;
use Librarian\Model\ODM\AuthorCollectionFinder;
use Librarian\Model\Book;
use Librarian\Model\BookRowSet;
use Librarian\Model\Filesystem\BookPlainTextFinder;
use Librarian\Model\Filesystem\BookCSVFinder;
use Librarian\Model\Filesystem\BookJSONFinder;
use Librarian\Model\ORM\BookFinder;
use Librarian\Model\ODM\BookCollectionFinder;
use Librarian\Util\Config;
use Librarian\Model\Filesystem\AuthorPlainText;
use Librarian\Model\Filesystem\AuthorCSV;
use Librarian\Model\Filesystem\AuthorJSON;
use Librarian\Model\Filesystem\BookPlainText;
use Librarian\Model\Filesystem\BookCSV;
use Librarian\Model\Filesystem\BookJSON;
use Librarian\Model\ORM\AuthorTableGateway;
use Librarian\Model\ORM\BookTableGateway;
use Librarian\Model\ODM\AuthorCollection;
use Librarian\Model\ODM\BookCollection;
use Librarian\Model\ORM\Table;
use Librarian\Model\ODM\Collection;

const AUTHOR_ROW_LENGTH = 65;
const BOOK_ROW_LENGTH = 89;

/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
function formatField($field, int $length)
{
    return str_pad($field, $length, ' ', STR_PAD_LEFT);
}

//author
function saveAuthor($lastName, $middleName, $firstName)
{
    $storageFormat = Config::get('storage_format'); 

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = (new AuthorPlainText())->save($lastName, $middleName, $firstName);
            break;
        case 'csv':
            $saved = (new AuthorCSV())->save($lastName, $middleName, $firstName);
            break;
        case 'json':
            $saved = (new AuthorJSON())->save($lastName, $middleName, $firstName);
            break;
        case 'rdb':
            $saved = (new AuthorTableGateway())->save($lastName, $middleName, $firstName);
            break;
        case 'ddb':
            $saved = (new AuthorCollection())->save($lastName, $middleName, $firstName);                    
    }
    return $saved;
}

function listAuthorsInTable()
{
    try {
        $authors = getAuthorFinder()->readAll();        
    } catch(\Exception $e) {
        $authors = new AuthorRowSet();
    }
    $html = '';
    foreach($authors as $author){
        $html.='<tr>';
        $html.='<td><a href="author.edit.php?code=' . $author->code . '">' . $author->code . '</a><td>';
        $html.="<td>{$author->firstName} {$author->middleName} {$author->lastName}<td>";
        $html.='<td><a href="author.delete.php?code=' . $author->code . '">remove</a><td>';
        $html.='</tr>';
    }
    return $html;
}

function getAuthorFinder()
{
    $storageFormat = Config::get('storage_format');
    switch($storageFormat){
        case 'txt':
            prepareFile('author');
            return new AuthorPlainTextFinder();
        case 'csv':
            prepareFile('author');
            return new AuthorCSVFinder();
        case 'json':
            prepareFile('author');
            return new AuthorJSONFinder();
        case 'rdb':
            return new AuthorFinder();
        case 'ddb':
            return new AuthorCollectionFinder();
    }
    throw new \Exception('invalid storage format');
}

function listAuthorsForSelect($code)
{
    try {
        $authors = getAuthorFinder()->readAll();        
    } catch(\Exception $e) {
        $authors = new AuthorRowSet();
    }
    $html = '';
    foreach($authors as $author){
        $authorName = $author->firstName . ' ' . $author->middleNname . ' ' . $author->lastName;
        $html.='<option value="' . $author->code . '"' . ($author->code == $code ? ' selected ' : '') . '>' . $authorName . '</option>';
    }
    return $html;
}

function getAuthorByCode($code)
{
    try {
        $author = getAuthorFinder()->readByCode($code);
    } catch(\Exception $e) {
        $author = new Author();
    }
    return $author;
}

function updateAuthor($code, $lastName, $middleName, $firstName)
{
    $storageFormat = Config::get('storage_format');

    $data = [
        'last_name' => $lastName,
        'middle_name' => $middleName,
        'first_name' => $firstName
    ];

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = (new AuthorPlainText())->update($code, $data);
            break;
        case 'csv':
            $saved = (new AuthorCSV())->update($code, $data);
            break;
        case 'json':
            $saved = (new AuthorJSON())->update($code, $data);
            break;
        case 'rdb':
            $saved = (new AuthorTableGateway())->update($code, $data);
            break;
        case 'ddb':
            $saved = (new AuthorCollection())->update($code, $data);
    }
    return $saved;
}

function deleteAuthor($code)
{
    $storageFormat = Config::get('storage_format');

    $deleted = false;
    switch($storageFormat){
        case 'txt':
            $deleted = (new AuthorPlainText())->delete($code);
            break;
        case 'csv':
            $deleted = (new AuthorCSV())->delete($code);
            break;
        case 'json':
            $deleted = (new AuthorJSON())->delete($code);
            break;
        case 'rdb':
            $deleted = (new AuthorTableGateway())->delete($code);
            break;
        case 'ddb':
            $deleted = (new AuthorCollection())->delete($code);
    }
    return $deleted;
}

//book
function saveBook($title, $authorCode)
{
    $storageFormat = Config::get('storage_format');

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = (new BookPlainText())->save($title, $authorCode);
            break;
        case 'csv':
            $saved = (new BookCSV())->save($title, $authorCode);
            break;
        case 'json':
            $saved = (new BookJSON())->save($title, $authorCode);
            break;
        case 'rdb':
            $saved = (new BookTableGateway())->save($title, $authorCode);
            break;
        case 'ddb':
            $saved = (new BookCollection())->save($title, $authorCode);
    }
    return $saved;
}

function listBooksInTable()
{
    try {
        $books = getBookFinder()->readAll();        
    } catch(\Exception $e) {
        $books = new BookRowSet();
    }
    $html = '';
    foreach($books as $book){
        $html.='<tr>';
        $html.='<td><a href="book.edit.php?code=' . $book->code . '">' . $book->code . '</a><td>';
        $html.="<td>{$book->title}<td>";
        $author = getAuthorByCode($book->author->code);
        $authorName = $author->firstName . ' ' . $author->middleName . ' ' . $author->lastName;
        $html.='<td>' . $authorName . '<td>';
        $html.='<td><a href="book.delete.php?code=' . $book->code . '">remove</a><td>';
        $html.='</tr>';
    }
    return $html;
}

function getBookFinder()
{
    $storageFormat = Config::get('storage_format');
    switch($storageFormat){
        case 'txt':
            prepareFile('book');
            return new BookPlainTextFinder();
        case 'csv':
            prepareFile('book');
            return new BookCSVFinder();
        case 'json':
            prepareFile('book');
            return new BookJSONFinder();
        case 'rdb':
            return new BookFinder();
        case 'ddb':
            return new BookCollectionFinder();
    }
    throw new \Exception('invalid storage format');
}


function getBookByCode($code)
{
    try {
        $book = getBookFinder()->readByCode($code);        
    } catch(\Exception $e) {
        $book = new Book();
    }
    return $book;
}

function updateBook($code, $title, $authorCode)
{
    $storageFormat = Config::get('storage_format');

    $data = [
        'title' => $title,
        'author_code' => $authorCode
    ];

    $saved = false;
    switch($storageFormat){
        case 'txt':
            $saved = (new BookPlainText())->update($code, $data);
            break;
        case 'csv':
            $saved = (new BookCSV())->update($code, $data);
            break;
        case 'json':
            $saved = (new BookJSON())->update($code, $data);
            break;
        case 'rdb':
            $saved = (new BookTableGateway())->update($code, $data);
            break;
        case 'ddb':
            $saved = (new BookCollection())->update($code, $data);
    }
    return $saved;
}

function deleteBook($code)
{
    $storageFormat = Config::get('storage_format');

    $deleted = false;
    switch($storageFormat){
        case 'txt':
            $deleted = (new BookPlainText())->delete($code);
            break;
        case 'csv':
            $deleted = (new BookCSV())->delete($code);
            break;
        case 'json':
            $deleted = (new BookJSON())->delete($code);
            break;
        case 'rdb':
            $deleted = (new BookTableGateway())->delete($code);
            break;
        case 'ddb':
            $deleted = (new BookCollection())->delete($code);            
    }
    return $deleted;
}

function getPathForFile(string $entity)
{
    $storageFormat = Config::get('storage_format');

    $path = '';
    switch($storageFormat){
        case 'txt':
            $path = Config::get($entity . '_plaintext_filepath');
            break;
        case 'csv':
            $path = Config::get($entity . '_csv_filepath');
            break;
        case 'json':
            $path = Config::get($entity . '_json_filepath');
    }
    return $path;
}

function prepareFile(string $entity)
{
    $path = getPathForFile($entity);
    if (!file_exists($path)){
        $handle = fopen($path,'w');
        fclose($handle);
    }
}

function replaceConfigFileContent(string $search, string $replace)
{
    $configFileName = 'book.config.php';
    if ((defined('LIBRARIAN_TEST_ENVIRONMENT') && LIBRARIAN_TEST_ENVIRONMENT) ||
    getenv('LIBRARIAN_TEST_ENVIRONMENT')){
        $configFileName = 'book.config.test.php';
    }
    $configPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $configFileName;
    $content = file_get_contents($configPath);
    $content = str_replace($search, $replace, $content);
    file_put_contents($configPath, $content);
}

function clearEntity(string $entity)
{
    $storageFormat = Config::get('storage_format');

    $path = '';
    switch($storageFormat){
        case 'txt':
        case 'csv':
        case 'json':
            unlink(getPathForFile($entity));
            break;
        case 'rdb':
            (new Table($entity . 's'))->truncate();
            break;
        case 'ddb':
            (new Collection($entity . 's'))->drop();            
    }
}

