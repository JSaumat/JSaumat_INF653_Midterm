<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Jose Saumat

File: Author.php

Description:
This model handles database operations related to authors.
It includes methods for retrieving authors, retrieving a
specific author by ID, checking if an author exists, and
creating new authors in the database.
*/

class Author {

  private $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function readAll() {
    $sql = "SELECT id, author FROM authors ORDER BY id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt;
  }

  public function readById($id) {
    $sql = "SELECT id, author FROM authors WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }

  public function exists($id) {
    $sql = "SELECT 1 FROM authors WHERE id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn() !== false;
  }

  public function create($authorName) {
    $sql = "INSERT INTO authors (author) VALUES (:author) RETURNING id, author";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":author", $authorName, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}