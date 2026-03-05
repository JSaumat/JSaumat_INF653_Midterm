<?php
class Database {
  private $conn;

  public function connect() {
    $host = getenv("HOST");
    $port = getenv("PORT");
    $dbname = getenv("DBNAME");
    $username = getenv("USERNAME");
    $password = getenv("PASSWORD");

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

    try {
      $this->conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]);
    } catch (PDOException $e) {
      // keep error message simple for the assignment
      die(json_encode(["message" => "Connection Error"]));
    }

    return $this->conn;
  }
}