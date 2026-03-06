# Quotes API (Midterm Project)

**Student:** Jose Saumat 

**Course:** INF 653 Back End Web Development  

**Deployed Homepage:** 
https://jsaumat-inf653-midterm.onrender.com

**API Root:**
https://jsaumat-inf653-midterm.onrender.com/api

## Description
A PHP OOP REST API that supports CRUD operations for quotes using PostgreSQL.

## Base URL
`/api`

## Endpoints

### Quotes
- GET `/api/quotes/`
- GET `/api/quotes/?id=1`
- GET `/api/quotes/?author_id=1`
- GET `/api/quotes/?category_id=1`
- GET `/api/quotes/?author_id=1&category_id=1`
- POST `/api/quotes/`  
  Body JSON: `{ "quote": "...", "author_id": 1, "category_id": 1 }`
- PUT `/api/quotes/`  
  Body JSON: `{ "id": 1, "quote": "...", "author_id": 1, "category_id": 1 }`
- DELETE `/api/quotes/`  
  Body JSON: `{ "id": 1 }`

### Authors
- GET `/api/authors/`
- GET `/api/authors/?id=1`
- POST `/api/authors/`  
  Body JSON: `{ "author": "Name" }`

### Categories
- GET `/api/categories/`
- GET `/api/categories/?id=1`
- POST `/api/categories/`  
  Body JSON: `{ "category": "Name" }`

## Testing
Use Postman:
- Set method (GET/POST/PUT/DELETE)
- Set URL
- For POST/PUT/DELETE set Body → raw → JSON and add `Content-Type: application/json`