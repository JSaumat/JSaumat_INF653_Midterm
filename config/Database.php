<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Jose Saumat

File: Database.php

Description:
This file contains the Database class used to establish a connection
to the PostgreSQL database using PDO. The connection values are
retrieved from environment variables provided by the hosting
environment (Render) or local development environment.
*/


// Database class responsible for creating a database connection
class Database {

  // Variable that will store the database connection object
  private $conn;

  // Method used to establish and return a database connection
  public function connect() {

    // Retrieve database host from environment variables
    $host = getenv("HOST");

    // Retrieve database port.
    // If DB_PORT is not available, fall back to PORT.
    $port = getenv("DB_PORT") ?: getenv("PORT");

    // Retrieve the database name from environment variables
    $dbname = getenv("DBNAME");

    // Retrieve the database username
    $username = getenv("USERNAME");

    // Retrieve the database password
    $password = getenv("PASSWORD");


    // Build the PDO Data Source Name (DSN) string
    // This specifies PostgreSQL, the host, port, database name,
    // and requires SSL for secure connections (needed on Render)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";


    // Attempt to create the database connection using PDO
    try {

      // Create a new PDO instance using the DSN and credentials
      $this->conn = new PDO($dsn, $username, $password, [

        // Configure PDO to throw exceptions when errors occur
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]);

    } catch (PDOException $e) {

      // If the connection fails, return a simple JSON error message
      // The assignment requests minimal error details
      die(json_encode(["message" => "Connection Error"]));
    }

    // Return the successful database connection
    return $this->conn;
  }
}