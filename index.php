<?php

/*
INF653 Back End Web Development
Midterm Project – Quotes REST API

Student: Jose Saumat

File: index.php

Description:
This is the public homepage for the Quotes API. It provides
basic information about the API and lists available endpoints
for interacting with quotes, authors, and categories.
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quotes API</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 900px;
      margin: 40px auto;
      padding: 0 16px;
      line-height: 1.6;
    }
    code {
      background: #f4f4f4;
      padding: 2px 6px;
      border-radius: 4px;
    }
    ul {
      line-height: 1.8;
    }
    a {
      color: #0066cc;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h1>Quotes API (Midterm Project)</h1>

  <p><strong>Student:</strong> Jose Saumat</p>
  <p><strong>Course:</strong> INF 653 Back End Web Development</p>

  <h2>Deployed Homepage</h2>
  <p>
    <a href="https://jsaumat-inf653-midterm.onrender.com">
      https://jsaumat-inf653-midterm.onrender.com
    </a>
  </p>

  <h2>API Root</h2>
  <p>
    <a href="https://jsaumat-inf653-midterm.onrender.com/api">
      https://jsaumat-inf653-midterm.onrender.com/api
    </a>
  </p>

  <h2>Description</h2>
  <p>
    A PHP OOP REST API that supports CRUD operations for quotes using PostgreSQL.
  </p>

  <h2>Base URL</h2>
  <p><code>/api</code></p>

  <h2>Live Example</h2>
  <p>You can test the deployed API directly:</p>
  <p>
    <a href="https://jsaumat-inf653-midterm.onrender.com/api/quotes/">
      GET https://jsaumat-inf653-midterm.onrender.com/api/quotes/
    </a>
  </p>
  <p>This request returns a JSON array of quotes from the database.</p>

  <h2>Endpoints</h2>

  <h3>Quotes</h3>
  <ul>
    <li><code>GET /api/quotes/</code></li>
    <li><code>GET /api/quotes/?id=1</code></li>
    <li><code>GET /api/quotes/?author_id=1</code></li>
    <li><code>GET /api/quotes/?category_id=1</code></li>
    <li><code>GET /api/quotes/?author_id=1&amp;category_id=1</code></li>
    <li><code>POST /api/quotes/</code> (JSON: quote, author_id, category_id)</li>
    <li><code>PUT /api/quotes/</code> (JSON: id, quote, author_id, category_id)</li>
    <li><code>DELETE /api/quotes/</code> (JSON: id)</li>
  </ul>

  <h3>Authors</h3>
  <ul>
    <li><code>GET /api/authors/</code></li>
    <li><code>GET /api/authors/?id=1</code></li>
    <li><code>POST /api/authors/</code> (JSON: author)</li>
    <li><code>PUT /api/authors/</code> (JSON: id, author)</li>
    <li><code>DELETE /api/authors/</code> (JSON: id)</li>
  </ul>

  <h3>Categories</h3>
  <ul>
    <li><code>GET /api/categories/</code></li>
    <li><code>GET /api/categories/?id=1</code></li>
    <li><code>POST /api/categories/</code> (JSON: category)</li>
    <li><code>PUT /api/categories/</code> (JSON: id, category)</li>
    <li><code>DELETE /api/categories/</code> (JSON: id)</li>
  </ul>

  <h2>Testing</h2>
  <p>Use Postman to test the API endpoints and JSON request bodies.</p>
</body>
</html>