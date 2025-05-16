<?php
/**
 * @author Flávio Gomes da Silva Lisboa
 * @license LGPL-3.0 license <https://www.gnu.org/licenses/lgpl-3.0.html.en>
 */
// author
function saveAuthorInCollection($lastName, $middleName, $firstName)
{	
    $database = getMongoDbConnection();
    $insertOneResult = $database->authors->insertOne([
        'code' => $database->authors->countDocuments() + 1,
        'last_name' => $lastName,
        'middle_name' => $middleName,
        'first_name' => $firstName
    ]);
    return $insertOneResult->getInsertedCount() == 1;
}

function readAuthorInCollectionByCode(int $code)
{
    $database = getMongoDbConnection();    
    $result = $database->authors->findOne(['code' => $code]);
    if (is_null($result)){
        return [];
    }
    return $result;
}

function readAuthorsInCollection()
{
    $database = getMongoDbConnection();
    $result = $database->authors->find();
    if (is_null($result)){
        return [];
    }
    return $result->toArray();
}

function updateAuthorInCollection(int $code, array $data)
{
    $database = getMongoDbConnection();
    $result = $database->authors->updateOne(
        ['code' => $code],
        ['$set' => $data]
    );
    return $result->getModifiedCount() == 1;
}

function deleteAuthorInCollection(int $code)
{
    $database = getMongoDbConnection();
    $result = $database->authors->deleteOne(['code' => $code]);
    return $result->getDeletedCount() == 1;
}
//book
function saveBookInCollection($title, int $authorCode)
{	
    $database = getMongoDbConnection();
    $insertOneResult = $database->books->insertOne([
        'code' => $database->books->countDocuments() + 1,
        'title' => $title,
        'author_code' => $authorCode        
    ]);
    return $insertOneResult->getInsertedCount() == 1;    
}

function readBookInCollectionByCode(int $code)
{
    $database = getMongoDbConnection();    
    $result = $database->books->findOne(['code' => $code]);
    if (is_null($result)){
        return [];
    }
    return $result;
}

function readBooksInCollection()
{
    $database = getMongoDbConnection();
    $result = $database->books->find();
    if (is_null($result)){
        return [];
    }
    return $result->toArray();
}

function updateBookInCollection(int $code, array $data)
{
    $database = getMongoDbConnection();
    $result = $database->books->updateOne(
        ['code' => $code],
        ['$set' => $data]
    );
    return $result->getModifiedCount() == 1;
}

function deleteBookInCollection(int $code)
{
    $database = getMongoDbConnection();
    $result = $database->books->deleteOne(['code' => $code]);
    return $result->getDeletedCount() == 1;
}

function getMongoDbConnection()
{   
    $database = getConfig()['db']['database']; 
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    return $mongo->$database;
}

function dropCollection(string $collectionName)
{
    $database = getMongoDbConnection();
    $collection = $database->$collectionName;
    $collection->drop();
}
