<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Student: Jose Saumat

File: api/index.php

Description:
This endpoint serves as the root entry point for the Quotes API.
It returns a simple JSON message confirming the API is running
and lists the available resource endpoints.

Supported Methods:
GET - Display API information and available endpoints
*/


// Allow requests from any origin (CORS support)
header("Access-Control-Allow-Origin: *");

// Specify which HTTP methods this API allows
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific request headers from the client
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Indicate that the API response will be JSON formatted
header("Content-Type: application/json; charset=UTF-8");


// Handle CORS preflight requests sent by browsers
// Browsers send an OPTIONS request before certain API calls
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200); // Return success status
  exit;                    // Stop further script execution
}


// Return a simple JSON response describing the API
echo json_encode([
  "message" => "Quotes API", // Basic message identifying the API

  // List of available API endpoints
  "endpoints" => [
    "quotes" => "/api/quotes/",        // Endpoint for quote resources
    "authors" => "/api/authors/",      // Endpoint for author resources
    "categories" => "/api/categories/" // Endpoint for category resources
  ]
]);