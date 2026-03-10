<?php

header("Content-Type: application/json; charset=UTF-8");

echo json_encode([
  "message" => "Quotes API",
  "endpoints" => [
    "quotes" => "/api/quotes/",
    "authors" => "/api/authors/",
    "categories" => "/api/categories/"
  ]
]);