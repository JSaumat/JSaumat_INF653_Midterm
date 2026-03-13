<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Student: Jose Saumat

File: api/authors/index.php

Description:
This endpoint handles HTTP requests for author resources.

Supported Methods:
GET    - Retrieve all authors or a specific author by ID
POST   - Create a new author
PUT    - Update an existing author
DELETE - Delete an existing author
*/


// Allow requests from any origin (CORS support)
header("Access-Control-Allow-Origin: *");

// Define which HTTP methods this API endpoint will accept
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers from the client
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Specify that responses will be JSON formatted
header("Content-Type: application/json; charset=UTF-8");


// Handle CORS preflight requests (sent by browsers before certain API calls)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200); // Return success status
  exit;                    // Stop further execution
}


// Load database connection class
require_once "../../config/Database.php";

// Load Author model class
require_once "../../models/Author.php";


// Create database object
$database = new Database();

// Establish database connection
$db = $database->connect();


// Create Author model instance and pass in database connection
$author = new Author($db);


// Store the HTTP request method (GET, POST, PUT, DELETE)
$method = $_SERVER["REQUEST_METHOD"];


// ---------- GET ----------
if ($method === "GET") {

  // Check if an ID was provided in the query string
  $id = isset($_GET["id"]) ? (int)$_GET["id"] : null;

  // If ID exists, retrieve a single author
  if ($id !== null && $id > 0) {

    // Call model function to read author by ID
    $stmt = $author->readById($id);

    // Fetch result as associative array
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no author found, return error message
    if (!$row) {
      echo json_encode(["message" => "author_id Not Found"]);
      exit;
    }

    // Return author record as JSON
    echo json_encode($row);
    exit;
  }

  // If no ID provided, retrieve all authors
  $stmt = $author->readAll();

  // Fetch all rows as associative arrays
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // If no authors found, return error message
  if (!$rows || count($rows) === 0) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  // Return all authors as JSON
  echo json_encode($rows);
  exit;
}


// ---------- POST ----------
if ($method === "POST") {

  // Read raw JSON input from request body and decode it
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate required parameter "author"
  if (!isset($data["author"]) || trim($data["author"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Call model to create new author record
  $created = $author->create(trim($data["author"]));

  // Return created author as JSON
  echo json_encode($created);
  exit;
}


// ---------- PUT ----------
if ($method === "PUT") {

  // Read JSON request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate required parameters (id and author name)
  if (!isset($data["id"]) || !isset($data["author"]) || trim($data["author"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Convert id to integer
  $id = (int)$data["id"];

  // Check if author exists before attempting update
  if (!$author->exists($id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  // Update author record
  $updated = $author->update($id, trim($data["author"]));

  // Return updated author data
  echo json_encode($updated);
  exit;
}


// ---------- DELETE ----------
if ($method === "DELETE") {

  // Read JSON request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Ensure ID parameter exists
  if (!isset($data["id"])) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Convert id to integer
  $id = (int)$data["id"];

  // Verify author exists before deletion
  if (!$author->exists($id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  // Attempt to delete author
  $ok = $author->delete($id);

  // If deletion succeeded, return deleted ID
  if ($ok) {
    echo json_encode(["id" => $id]);
  } else {
    // If deletion failed, return error message
    echo json_encode(["message" => "author_id Not Found"]);
  }
  exit;
}


// If request method is not supported, return 405 error
http_response_code(405);

// Return JSON error message
echo json_encode(["message" => "Method Not Allowed"]);