<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../../config/Database.php";
require_once "../../models/Category.php";

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$method = $_SERVER["REQUEST_METHOD"];

// ---------- GET ----------
if ($method === "GET") {

  $id = isset($_GET["id"]) ? (int)$_GET["id"] : null;

  if ($id !== null && $id > 0) {
    $stmt = $category->readById($id);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
      echo json_encode(["message" => "category_id Not Found"]);
      exit;
    }

    echo json_encode($row);
    exit;
  }

  $stmt = $category->readAll();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (!$rows || count($rows) === 0) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit;
  }

  echo json_encode($rows);
  exit;
}

// ---------- POST ----------
if ($method === "POST") {

  $data = json_decode(file_get_contents("php://input"), true);

  if (!isset($data["category"]) || trim($data["category"]) === "") {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit;
  }

  $created = $category->create(trim($data["category"]));
  echo json_encode($created);
  exit;
}

http_response_code(405);
echo json_encode(["message" => "Method Not Allowed"]);