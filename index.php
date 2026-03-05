<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quotes API</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 0 16px; }
    code { background: #f4f4f4; padding: 2px 6px; border-radius: 4px; }
    ul { line-height: 1.8; }
  </style>
</head>
<body>
  <h1>Quotes API</h1>
  <p><strong>Created by:</strong> Jose Saumat</p>

  <h2>Base URL</h2>
  <p><code>/api</code></p>

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
  </ul>

  <h3>Categories</h3>
  <ul>
    <li><code>GET /api/categories/</code></li>
    <li><code>GET /api/categories/?id=1</code></li>
    <li><code>POST /api/categories/</code> (JSON: category)</li>
  </ul>

  <p>Test with Postman using the endpoints above.</p>
</body>
</html>