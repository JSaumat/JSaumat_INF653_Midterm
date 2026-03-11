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

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200);
  exit;
}

require_once "../../config/Database.php";
require_once "../../models/Author.php";

$database = new Database();
$db = $database->connect();

$author = new Author($db);

$method = $_SERVER["REQUEST_METHOD"];

// ---------- GET ----------
if ($method === "GET") {

  $id = isset($_GET["id"]) ? (int)$_GET["id"] : null;

  if ($id !== null && $id > 0) {
    $stmt = $author->readById($id);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      echo json_encode(["message" => "author_id Not Found"]);
      exit;
    }

    echo json_encode($row);
    exit;
  }

  $stmt = $author->readAll();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (!$rows || count($rows) === 0) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  echo json_encode($rows);
  exit;
}

// ---------- POST ----------
if ($method === "POST") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (!isset($data["author"]) || trim($data["author"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $created = $author->create(trim($data["author"]));
  echo json_encode($created);
  exit;
}

// ---------- PUT ----------
if ($method === "PUT") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (!isset($data["id"]) || !isset($data["author"]) || trim($data["author"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $id = (int)$data["id"];

  if (!$author->exists($id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  $updated = $author->update($id, trim($data["author"]));
  echo json_encode($updated);
  exit;
}

// ---------- DELETE ----------
if ($method === "DELETE") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (!isset($data["id"])) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $id = (int)$data["id"];

  if (!$author->exists($id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  $ok = $author->delete($id);

  if ($ok) {
    echo json_encode(["id" => $id]);
  } else {
    echo json_encode(["message" => "author_id Not Found"]);
  }
  exit;
}

http_response_code(405);
echo json_encode(["message" => "Method Not Allowed"]);