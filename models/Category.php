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


// Category model class responsible for interacting with the categories table
class Category {

  // Variable that stores the database connection
  private $conn;


  // Constructor receives the database connection and stores it
  public function __construct($db) {
    $this->conn = $db;
  }


  // Retrieve all categories from the database
  public function readAll() {

    // SQL query to select all category records ordered by ID
    $sql = "SELECT id, category FROM categories ORDER BY id";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Return the PDO statement so the caller can fetch results
    return $stmt;
  }


  // Retrieve a single category by its ID
  public function readById($id) {

    // SQL query to retrieve one category using a parameterized ID
    $sql = "SELECT id, category FROM categories WHERE id = :id LIMIT 1";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the ID parameter as an integer to prevent SQL injection
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Return the PDO statement so the caller can fetch the result
    return $stmt;
  }


  // Check whether a category with a given ID exists
  public function exists($id) {

    // Query checks if at least one record exists with the given ID
    $sql = "SELECT 1 FROM categories WHERE id = :id LIMIT 1";

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


  // Insert a new category into the database
  public function create($categoryName) {

    // SQL query inserts a new category and returns the created record
    $sql = "INSERT INTO categories (category) VALUES (:category) RETURNING id, category";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the category name as a string
    $stmt->bindValue(":category", $categoryName, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Return the inserted category record as an associative array
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  // Update an existing category
  public function update($id, $categoryName) {

    // SQL query updates the category name for the specified ID
    // RETURNING allows PostgreSQL to return the updated record
    $sql = "UPDATE categories
            SET category = :category
            WHERE id = :id
            RETURNING id, category";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare($sql);

    // Bind the ID parameter
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    // Bind the new category name
    $stmt->bindValue(":category", $categoryName, PDO::PARAM_STR);

    // Execute the update
    $stmt->execute();

    // Return the updated category record
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  // Delete a category by ID
  public function delete($id) {

    // SQL query to remove a category record
    $sql = "DELETE FROM categories WHERE id = :id";

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