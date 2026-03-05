<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../../config/Database.php";
require_once "../../models/Quote.php";
require_once "../../models/Author.php";
require_once "../../models/Category.php";

$database = new Database();
$db = $database->connect();

$quoteModel = new Quote($db);
$authorModel = new Author($db);
$categoryModel = new Category($db);

$method = $_SERVER["REQUEST_METHOD"];

/* -------------------- GET -------------------- */
if ($method === "GET") {

  $id = isset($_GET["id"]) ? (int)$_GET["id"] : null;
  $author_id = isset($_GET["author_id"]) ? (int)$_GET["author_id"] : null;
  $category_id = isset($_GET["category_id"]) ? (int)$_GET["category_id"] : null;

  if ($id !== null && $id > 0) {
    $stmt = $quoteModel->readById($id);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } elseif ($author_id !== null || $category_id !== null) {
    $stmt = $quoteModel->readFiltered($author_id ?: null, $category_id ?: null);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $stmt = $quoteModel->readAll();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  if (!$rows || count($rows) === 0) {
    echo json_encode(["message" => "No Quotes Found"]);
    exit;
  }

  echo json_encode($rows);
  exit;
}

/* -------------------- POST -------------------- */
if ($method === "POST") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (
    !isset($data["quote"]) || trim($data["quote"]) === "" ||
    !isset($data["author_id"]) || !isset($data["category_id"])
  ) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $quoteText = trim($data["quote"]);
  $author_id = (int)$data["author_id"];
  $category_id = (int)$data["category_id"];

  if (!$authorModel->exists($author_id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  if (!$categoryModel->exists($category_id)) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  $created = $quoteModel->create($quoteText, $author_id, $category_id);
  echo json_encode($created);
  exit;
}

/* -------------------- PUT -------------------- */
if ($method === "PUT") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (
    !isset($data["id"]) ||
    !isset($data["quote"]) || trim($data["quote"]) === "" ||
    !isset($data["author_id"]) || !isset($data["category_id"])
  ) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $id = (int)$data["id"];
  $quoteText = trim($data["quote"]);
  $author_id = (int)$data["author_id"];
  $category_id = (int)$data["category_id"];

  if (!$quoteModel->quoteExists($id)) {
    echo json_encode(["message" => "No Quotes Found"]);
    exit;
  }

  if (!$authorModel->exists($author_id)) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit;
  }

  if (!$categoryModel->exists($category_id)) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  $updated = $quoteModel->update($id, $quoteText, $author_id, $category_id);
  echo json_encode($updated);
  exit;
}

/* -------------------- DELETE -------------------- */
if ($method === "DELETE") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (!isset($data["id"])) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $id = (int)$data["id"];

  if (!$quoteModel->quoteExists($id)) {
    echo json_encode(["message" => "No Quotes Found"]);
    exit;
  }

  $ok = $quoteModel->delete($id);

  if ($ok) {
    echo json_encode(["id" => $id]);
  } else {
    echo json_encode(["message" => "No Quotes Found"]);
  }
  exit;
}

http_response_code(405);
echo json_encode(["message" => "Method Not Allowed"]);