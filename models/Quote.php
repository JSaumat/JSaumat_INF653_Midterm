<?php
class Quote {
  private $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  // GET all quotes (no filters)
  public function readAll() {
    $sql = "
      SELECT q.id, q.quote, a.author, c.category
      FROM quotes q
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id
      ORDER BY q.id
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt;
  }

  // GET one quote by id
  public function readById($id) {
    $sql = "
      SELECT q.id, q.quote, a.author, c.category
      FROM quotes q
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id
      WHERE q.id = :id
      LIMIT 1
    ";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }

  // GET quotes filtered by author_id, category_id, or both
  public function readFiltered($author_id = null, $category_id = null) {
    $sql = "
      SELECT q.id, q.quote, a.author, c.category
      FROM quotes q
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id
      WHERE 1=1
    ";

    if ($author_id !== null) {
      $sql .= " AND q.author_id = :author_id";
    }
    if ($category_id !== null) {
      $sql .= " AND q.category_id = :category_id";
    }

    $sql .= " ORDER BY q.id";

    $stmt = $this->conn->prepare($sql);

    if ($author_id !== null) {
      $stmt->bindValue(":author_id", $author_id, PDO::PARAM_INT);
    }
    if ($category_id !== null) {
      $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt;
  }

  public function quoteExists($id) {
  $sql = "SELECT 1 FROM quotes WHERE id = :id LIMIT 1";
  $stmt = $this->conn->prepare($sql);
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchColumn() !== false;
  }

public function create($quoteText, $author_id, $category_id) {
  $sql = "INSERT INTO quotes (quote, author_id, category_id)
          VALUES (:quote, :author_id, :category_id)
          RETURNING id, quote, author_id, category_id";
  $stmt = $this->conn->prepare($sql);
  $stmt->bindValue(":quote", $quoteText, PDO::PARAM_STR);
  $stmt->bindValue(":author_id", $author_id, PDO::PARAM_INT);
  $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
  }

public function update($id, $quoteText, $author_id, $category_id) {
  $sql = "UPDATE quotes
          SET quote = :quote, author_id = :author_id, category_id = :category_id
          WHERE id = :id
          RETURNING id, quote, author_id, category_id";
  $stmt = $this->conn->prepare($sql);
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  $stmt->bindValue(":quote", $quoteText, PDO::PARAM_STR);
  $stmt->bindValue(":author_id", $author_id, PDO::PARAM_INT);
  $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
  }

public function delete($id) {
  $sql = "DELETE FROM quotes WHERE id = :id";
  $stmt = $this->conn->prepare($sql);
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->rowCount() > 0;
  }
}