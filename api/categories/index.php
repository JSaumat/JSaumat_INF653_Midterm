<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Student: Jose Saumat

File: api/categories/index.php

Description:
This endpoint handles HTTP requests for category resources.

Supported Methods:
GET    - Retrieve all categories or a specific category by ID
POST   - Create a new category
PUT    - Update an existing category
DELETE - Delete an existing category
*/


// Allow requests from any origin (CORS support)
header("Access-Control-Allow-Origin: *");

// Specify the HTTP methods this API endpoint accepts
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow certain headers from the client
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Set the response content type to JSON
header("Content-Type: application/json; charset=UTF-8");


// Handle CORS preflight request sent by browsers
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200); // Return success response
  exit;                    // Stop execution
}


// Load the database connection class
require_once "../../config/Database.php";

// Load the Category model class
require_once "../../models/Category.php";


// Create a database object
$database = new Database();

// Connect to the database
$db = $database->connect();


// Create a Category model instance and pass in the database connection
$category = new Category($db);


// Store the HTTP request method (GET, POST, PUT, DELETE)
$method = $_SERVER["REQUEST_METHOD"];


// ---------- GET ----------
if ($method === "GET") {

  // Check if an ID was passed in the query string
  $id = isset($_GET["id"]) ? (int)$_GET["id"] : null;

  // If an ID is provided, retrieve a single category
  if ($id !== null && $id > 0) {

    // Call the model method to read a category by ID
    $stmt = $category->readById($id);

    // Fetch the result as an associative array
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the category does not exist, return error message
    if (!$row) {
      echo json_encode(["message" => "category_id Not Found"]);
      exit;
    }

    // Return the category record as JSON
    echo json_encode($row);
    exit;
  }

  // If no ID was provided, retrieve all categories
  $stmt = $category->readAll();

  // Fetch all records as associative arrays
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // If no categories exist, return error message
  if (!$rows || count($rows) === 0) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  // Return all categories as JSON
  echo json_encode($rows);
  exit;
}


// ---------- POST ----------
if ($method === "POST") {

  // Read raw JSON input from request body and decode into an array
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate that the required parameter "category" exists and is not empty
  if (!isset($data["category"]) || trim($data["category"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Call the model method to create a new category
  $created = $category->create(trim($data["category"]));

  // Return the created category as JSON
  echo json_encode($created);
  exit;
}


// ---------- PUT ----------
if ($method === "PUT") {

  // Read JSON data from the request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Validate required parameters (id and category name)
  if (!isset($data["id"]) || !isset($data["category"]) || trim($data["category"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Convert ID to integer
  $id = (int)$data["id"];

  // Verify the category exists before updating
  if (!$category->exists($id)) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  // Call the model method to update the category
  $updated = $category->update($id, trim($data["category"]));

  // Return the updated category record
  echo json_encode($updated);
  exit;
}


// ---------- DELETE ----------
if ($method === "DELETE") {

  // Read JSON request body
  $data = json_decode(file_get_contents("php://input"), true);

  // Ensure an ID was provided
  if (!isset($data["id"])) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  // Convert ID to integer
  $id = (int)$data["id"];

  // Check if the category exists before attempting deletion
  if (!$category->exists($id)) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  // Attempt to delete the category
  $ok = $category->delete($id);

  // If deletion succeeded, return the deleted ID
  if ($ok) {
    echo json_encode(["id" => $id]);
  } else {
    // If deletion failed, return an error message
    echo json_encode(["message" => "category_id Not Found"]);
  }
  exit;
}


// If the HTTP method is not supported, return a 405 error
http_response_code(405);

// Return error message in JSON format
echo json_encode(["message" => "Method Not Allowed"]);