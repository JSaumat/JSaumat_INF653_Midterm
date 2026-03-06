<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

ose Saumat

File: Database.php

Description:
This file contains the Database class used to establish a connection
to the PostgreSQL database using PDO. The connection values are
retrieved from environment variables provided by the hosting
environment (Render) or local development environment.
*/

class Database {
  private $conn;

  public function connect() {
    $host = getenv("HOST");
    $port = getenv("DB_PORT");
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