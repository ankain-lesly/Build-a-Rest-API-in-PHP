<?php 
namespace App\Models;

use App\Models\DataAccess;

class Product {
  private $DataAccess;

  public function __construct() {
    $this->DataAccess = new DataAccess();
  }


  // Get items/Collection
  public function getAll(): array {
    $sql = "SELECT * FROM books_data";

    return $this->DataAccess->fetchAll($sql);
  }



  // Get single Item/Resource
  public function get(int|string $keyword): bool|array {
    $sql = "SELECT * FROM books_data WHERE bookID = :id OR title = :title";
    
    return $this->DataAccess->
      fetchCustomData($sql, [$keyword, $keyword]);
  }



  // Create Book
  public function createBook(array $data): int|bool {
    $sql = "INSERT INTO books_data (title, author, description, URL, categoryID, userID) 
            VALUES (:title, :author, :description, :URL, :categoryID, :useID)";

    return $this->DataAccess->
      insertCustomData($sql, [
        $data['title'],
        $data['author'],
        $data['description'],
        $data['URL'],
        $data['categoryID'],
        $data['userID'],
      ]);
  }



  // Create Category
  public function createCategory(array $data): int|bool {
    $access_token = explode("@@", $data['_acc_token']);
    $token = $access_token[0];
    $userID = $access_token[1];

    $sql = "INSERT INTO categories (title, description, userID) 
            VALUES (:title, :description, :userID)";

    return $this->DataAccess->
      queryCustomData($sql, [
        $data['title'],
        $data['description'],
        $data['userID'],
      ]);
  }



  // DELETE BOOK
  public function deleteBook(int|string $id, string $auth_key): int {

    // Authenticate User before Delete
    $key = strtolower($auth_key);
    $users = ['asdf', 'aaaa', 'abcd', 'abab'];

    if(!in_array($key, $users)) {
      return 401;
    }

    // Delete User Specific Data || where User ID == auth_key
    $sql = "DELETE FROM books_data WHERE bookID = :key";
    
    return $this->DataAccess->
      queryCustomData($sql, $id);
  }



  // UPDATE BOOK
  public function updateBook($data) {

    $sql = "UPDATE books_data SET title = :title, author = :author, description = :description, URL = :URL, categoryID = :categoryID WHERE userID = :user";


    return $this->DataAccess->
      queryCustomData($sql, [
        $data['title'],
        $data['author'],
        $data['description'],
        $data['URL'],
        $data['category'],
        $data['user'],
      ]);
  }


   // Get items/Collection
  public function searchBook($keyword): array {
    $keyword = "%$keyword%";

    $sql = "SELECT * FROM books_data WHERE title LIKE :title OR author LIKE :author OR description LIKE :description";

    return $this->DataAccess->
      fetchCustomDataArray($sql, [
        $keyword,
        $keyword,
        $keyword
      ]);

  }
}