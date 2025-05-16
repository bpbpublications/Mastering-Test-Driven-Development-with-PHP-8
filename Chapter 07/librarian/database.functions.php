<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
// author
function saveAuthorInDatabase($lastName, $middleName, $firstName)
{	
    $mysqli = getConnection();
    $stmt = $mysqli->prepare('INSERT INTO authors(last_name,middle_name,first_name) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $lastName, $middleName, $firstName);
    return $stmt->execute();
}

function readAuthorInDatabaseByCode(int $code)
{
    $mysqli = getConnection();
    $result = $mysqli->query('SELECT * FROM authors WHERE code = ' . $code);
    if ($result === false){
        return [];
    }
    return $result->fetch_assoc();
}

function readAuthorsInDatabase()
{
    $mysqli = getConnection();
    $result = $mysqli->query('SELECT * FROM authors');
    if ($result === false){
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateAuthorInDatabase(int $code, array $data)
{
    $mysqli = getConnection();
    $placeholders = '';
    $types = '';
    $values = [];
    foreach($data as $field => $value){
        $placeholders .= $field . ' = ?,';
        $types .= 's';
        $values[] = $value;
    }
    $types .= 'i';
    $values[] = $code;
    $placeholders = substr($placeholders,0,strlen($placeholders)-1);
    $stmt = $mysqli->prepare('UPDATE authors SET ' . $placeholders . ' WHERE code = ?');
    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

function deleteAuthorInDatabase(int $code)
{
    $mysqli = getConnection();
    $stmt = $mysqli->prepare('DELETE FROM authors WHERE code = ?');
    $stmt->bind_param('i', $code);
    return $stmt->execute();
}
//book
function saveBookInDatabase($title, int $authorCode)
{	
    $mysqli = getConnection();
    $stmt = $mysqli->prepare('INSERT INTO books(title,author_code) VALUES (?, ?)');
    $stmt->bind_param('si', $title, $authorCode);
    return $stmt->execute();
}

function readBookInDatabaseByCode(int $code)
{
    $mysqli = getConnection();
    $result = $mysqli->query('SELECT * FROM books WHERE code = ' . $code);
    if ($result === false){
        return [];
    }
    return $result->fetch_assoc();
}

function readBooksInDatabase()
{
    $mysqli = getConnection();
    $result = $mysqli->query('SELECT * FROM books');
    if ($result === false){
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateBookInDatabase(int $code, array $data)
{
    $mysqli = getConnection();
    $placeholders = '';
    $types = '';
    $values = [];
    foreach($data as $field => $value){
        $placeholders .= $field . ' = ?,';
        $types .=  is_int($value) ? 'i' : 's';
        $values[] = $value;
    }
    $types .= 'i';
    $values[] = $code;
    $placeholders = substr($placeholders,0,strlen($placeholders)-1);
    $stmt = $mysqli->prepare('UPDATE books SET ' . $placeholders . ' WHERE code = ?');
    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

function deleteBookInDatabase(int $code)
{
    $mysqli = getConnection();
    $stmt = $mysqli->prepare('DELETE FROM books WHERE code = ?');
    $stmt->bind_param('i', $code);
    return $stmt->execute();
}

function getConnection()
{
    $db = getConfig()['db'];
    return new mysqli($db['host'], $db['username'], $db['password'], $db['database']);
}

function truncateTable(string $table)
{
    $mysqli = getConnection();
    $mysqli->query('DELETE FROM ' . $table);
    $mysqli->query('ALTER TABLE ' . $table . ' AUTO_INCREMENT = 1');
}