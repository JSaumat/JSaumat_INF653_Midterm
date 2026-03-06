<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Jose Saumat

File: Category.php

Description:
This model handles database operations related to categories.
It includes methods for retrieving categories, retrieving a
specific category by ID, checking if a category exists, and
creating new categories in the database.
*/

class Category {

  private $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function readAll() {
    $sql = "SELECT id, category FROM categories ORDER BY id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt;
  }

  public function readById($id) {
    $sql = "SELECT id, category FROM categories WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }

  public function exists($id) {
    $sql = "SELECT 1 FROM categories WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn() !== false;
  }

  public function create($categoryName) {
  $sql = "INSERT INTO categories (category) VALUES (:category) RETURNING id, category";
  $stmt = $this->conn->prepare($sql);
  $stmt->bindValue(":category", $categoryName, PDO::PARAM_STR);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}