<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Jose Saumat

File: api/quotes/index.php

Description:
This endpoint manages quote resources and supports full CRUD
operations.

Supported Methods:
GET    - Retrieve quotes or filter by ID, author, or category
POST   - Create a new quote
PUT    - Update an existing quote
DELETE - Remove a quote from the database
*/


// Allow requests from any origin (CORS support)
header("Access-Control-Allow-Origin: *");

// Define which HTTP methods this endpoint will accept
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers from the client
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Specify that responses will be returned as JSON
header("Content-Type: application/json; charset=UTF-8");


// Handle CORS preflight requests (sent automatically by browsers)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200); // Return success
  exit;                    // Stop execution
}


// Load required classes
require_once "../../config/Database.php";   // Database connection class
require_once "../../models/Quote.php";      // Quote model
require_once "../../models/Author.php";     // Author model
require_once "../../models/Category.php";   // Category model


// Create database object
$database = new Database();

// Establish database connection
$db = $database->connect();


// Create model instances and pass in database connection
$quoteModel = new Quote($db);
$authorModel = new Author($db);
$categoryModel = new Category($db);


// Store the HTTP request method (GET, POST, PUT, DELETE)
$method = $_SERVER["REQUEST_METHOD"];


/* -------------------- GET -------------------- */
if ($method === "GET") {

  // Retrieve optional query parameters from the URL
  $id = isset($_GET["id"]) ? (int)$_GET["id"] : null;
  $author_id = isset($_GET["author_id"]) ? (int)$_GET["author_id"] : null;
  $category_id = isset($_GET["category_id"]) ? (int)$_GET["category_id"] : null;

  // If a specific quote ID is requested, return a single quote object
  if ($id !== null && $id > 0) {

    // Query the database for the quote by ID
    $stmt = $quoteModel->readById($id);

    // Fetch the result as an associative array
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the quote does not exist, return an error message
    if (!$row) {
      echo json_encode(["message" => "No Quotes Found"]);
      exit;
    }

    // Return the quote as a JSON object
    echo json_encode($row);
    exit;
  }

  // If author/category filters are provided, return filtered results
  if ($author_id !== null || $category_id !== null) {

    // Call model method to retrieve filtered quotes
    $stmt = $quoteModel->readFiltered($author_id ?: null, $category_id ?: null);

    // Fetch all matching rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } else {

    // If no filters are used, retrieve all quotes
    $stmt = $quoteModel->readAll();

    // Fetch all rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // If no quotes are found, return error message
  if (!$rows || count($rows) === 0) {
    echo json_encode(["message" => "No Quotes Found"]);
    exit;
  }

  // Return quotes as a JSON array
  echo json_encode($rows);
  exit;
}


/* -------------------- POST -------------------- */
if ($method === "POST") {

  // Read JSON data sent in the request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate required parameters
  if (
    !isset($data["quote"]) || trim($data["quote"]) === "" ||
    !isset($data["author_id"]) || !isset($data["category_id"])
  ) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Store sanitized values
  $quoteText = trim($data["quote"]);
  $author_id = (int)$data["author_id"];
  $category_id = (int)$data["category_id"];

  // Verify that the author exists
  if (!$authorModel->exists($author_id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  // Verify that the category exists
  if (!$categoryModel->exists($category_id)) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  // Create the new quote record
  $created = $quoteModel->create($quoteText, $author_id, $category_id);

  // Return the created quote
  echo json_encode($created);
  exit;
}


/* -------------------- PUT -------------------- */
if ($method === "PUT") {

  // Read JSON data from request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate required parameters
  if (
    !isset($data["id"]) ||
    !isset($data["quote"]) || trim($data["quote"]) === "" ||
    !isset($data["author_id"]) || !isset($data["category_id"])
  ) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Extract and sanitize values
  $id = (int)$data["id"];
  $quoteText = trim($data["quote"]);
  $author_id = (int)$data["author_id"];
  $category_id = (int)$data["category_id"];

  // Verify the quote exists before updating
  if (!$quoteModel->quoteExists($id)) {
    echo json_encode(["message" => "No Quotes Found"]);
    exit;
  }

  // Verify the author exists
  if (!$authorModel->exists($author_id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  // Verify the category exists
  if (!$categoryModel->exists($category_id)) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  // Update the quote in the database
  $updated = $quoteModel->update($id, $quoteText, $author_id, $category_id);

  // Return the updated quote
  echo json_encode($updated);
  exit;
}


/* -------------------- DELETE -------------------- */
if ($method === "DELETE") {

  // Read JSON request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate required parameter
  if (!isset($data["id"])) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Convert ID to integer
  $id = (int)$data["id"];

  // Verify the quote exists before deleting
  if (!$quoteModel->quoteExists($id)) {
    echo json_encode(["message" => "No Quotes Found"]);
    exit;
  }

  // Attempt to delete the quote
  $ok = $quoteModel->delete($id);

  // If deletion succeeds, return deleted ID
  if ($ok) {
    echo json_encode(["id" => $id]);
  } else {
    // If deletion fails, return error message
    echo json_encode(["message" => "No Quotes Found"]);
  }
  exit;
}


// If request method is not supported, return HTTP 405 error
http_response_code(405);

// Return JSON error message
echo json_encode(["message" => "Method Not Allowed"]);