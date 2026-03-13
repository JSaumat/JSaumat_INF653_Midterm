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


// Author model class responsible for interacting with the authors table
class Author {

  // Variable that stores the database connection
  private $conn;


  // Constructor receives the database connection and stores it
  public function __construct($db) {
    $this->conn = $db;
  }


  // Retrieve all authors from the database
  public function readAll() {

    // SQL query to select all author records ordered by ID
    $sql = "SELECT id, author FROM authors ORDER BY id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Return the PDO statement so the caller can fetch results
    return $stmt;
  }


  // Retrieve a single author by its ID
  public function readById($id) {

    // SQL query to retrieve one author using a parameterized ID
    $sql = "SELECT id, author FROM authors WHERE id = :id LIMIT 1";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the ID parameter as an integer to prevent SQL injection
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Return the PDO statement so the caller can fetch the result
    return $stmt;
  }


  // Check whether an author with a given ID exists
  public function exists($id) {

    // Query checks if at least one record exists with the given ID
    $sql = "SELECT 1 FROM authors WHERE id = :id LIMIT 1";

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


  // Insert a new author into the database
  public function create($authorName) {

    // SQL query inserts a new author and returns the created row
    $sql = "INSERT INTO authors (author) VALUES (:author) RETURNING id, author";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the author name as a string
    $stmt->bindValue(":author", $authorName, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Return the inserted author record as an associative array
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  // Update an existing author
  public function update($id, $authorName) {

    // SQL query updates the author name for the specified ID
    // RETURNING allows PostgreSQL to return the updated record
    $sql = "UPDATE authors
            SET author = :author
            WHERE id = :id
            RETURNING id, author";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the ID parameter
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Bind the new author name
    $stmt->bindValue(":author", $authorName, PDO::PARAM_STR);

    // Execute the update
    $stmt->execute();

    // Return the updated author record
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } 


  // Delete an author by ID
  public function delete($id) {

    // SQL query to remove an author record
    $sql = "DELETE FROM authors WHERE id = :id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the ID parameter
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the delete operation
    $stmt->execute();

    // rowCount() returns the number of rows affected
    // If greater than 0, the delete was successful
    return $stmt->rowCount() > 0;
  }
}