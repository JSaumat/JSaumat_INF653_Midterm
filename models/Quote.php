<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Jose Saumat

File: Quote.php

Description:
This model manages all database operations related to quotes.
It includes methods to retrieve quotes, filter quotes by
author or category, create new quotes, update existing quotes,
and delete quotes from the database.
*/


// Quote model class responsible for interacting with the quotes table
class Quote {

  // Variable that stores the database connection
  private $conn;


  // Constructor receives the database connection and stores it
  public function __construct($db) {
    $this->conn = $db;
  }


  // Retrieve all quotes from the database (no filters)
  public function readAll() {

    // SQL query joins quotes with authors and categories
    // so the API returns readable author and category names
    $sql = "
      SELECT q.id, q.quote, a.author, c.category
      FROM quotes q
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id
      ORDER BY q.id
    ";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Return the PDO statement so the caller can fetch results
    return $stmt;
  }


  // Retrieve a single quote by its ID
  public function readById($id) {

    // SQL query joins related tables and retrieves one quote
    $sql = "
      SELECT q.id, q.quote, a.author, c.category
      FROM quotes q
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id
      WHERE q.id = :id
      LIMIT 1
    ";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the quote ID parameter as an integer
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Return the PDO statement so the caller can fetch the result
    return $stmt;
  }


  // Retrieve quotes filtered by author_id, category_id, or both
  public function readFiltered($author_id = null, $category_id = null) {

    // Base SQL query
    // WHERE 1=1 allows conditions to be appended dynamically
    $sql = "
      SELECT q.id, q.quote, a.author, c.category
      FROM quotes q
      JOIN authors a ON q.author_id = a.id
      JOIN categories c ON q.category_id = c.id
      WHERE 1=1
    ";

    // If an author_id filter is provided, add it to the query
    if ($author_id !== null) {
      $sql .= " AND q.author_id = :author_id";
    }

    // If a category_id filter is provided, add it to the query
    if ($category_id !== null) {
      $sql .= " AND q.category_id = :category_id";
    }

    // Order results by quote ID
    $sql .= " ORDER BY q.id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind author_id parameter if it exists
    if ($author_id !== null) {
      $stmt->bindValue(":author_id", $author_id, PDO::PARAM_INT);
    }

    // Bind category_id parameter if it exists
    if ($category_id !== null) {
      $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
    }

    // Execute the query
    $stmt->execute();

    // Return the PDO statement
    return $stmt;
  }


  // Check whether a quote with a given ID exists
  public function quoteExists($id) {

    // SQL query checks if a quote exists
    $sql = "SELECT 1 FROM quotes WHERE id = :id LIMIT 1";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the ID parameter
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // fetchColumn() returns false if no row exists
    // If a row exists, the function returns true
    return $stmt->fetchColumn() !== false;
  }


  // Insert a new quote into the database
  public function create($quoteText, $author_id, $category_id) {

    // SQL query inserts a new quote record
    // RETURNING allows PostgreSQL to return the created record
    $sql = "INSERT INTO quotes (quote, author_id, category_id)
            VALUES (:quote, :author_id, :category_id)
            RETURNING id, quote, author_id, category_id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the quote text
    $stmt->bindValue(":quote", $quoteText, PDO::PARAM_STR);

    // Bind the author ID
    $stmt->bindValue(":author_id", $author_id, PDO::PARAM_INT);

    // Bind the category ID
    $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);

    // Execute the insert operation
    $stmt->execute();

    // Return the inserted quote record
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  // Update an existing quote
  public function update($id, $quoteText, $author_id, $category_id) {

    // SQL query updates quote text, author, and category
    $sql = "UPDATE quotes
            SET quote = :quote, author_id = :author_id, category_id = :category_id
            WHERE id = :id
            RETURNING id, quote, author_id, category_id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the quote ID
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Bind the updated quote text
    $stmt->bindValue(":quote", $quoteText, PDO::PARAM_STR);

    // Bind the updated author ID
    $stmt->bindValue(":author_id", $author_id, PDO::PARAM_INT);

    // Bind the updated category ID
    $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);

    // Execute the update operation
    $stmt->execute();

    // Return the updated quote record
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  // Delete a quote by ID
  public function delete($id) {

    // SQL query removes a quote record from the database
    $sql = "DELETE FROM quotes WHERE id = :id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the quote ID
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the delete operation
    $stmt->execute();

    // rowCount() returns the number of rows affected
    // If greater than 0, the delete was successful
    return $stmt->rowCount() > 0;
  }
}