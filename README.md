# PHP REST API

A simple RESTful API built with PHP (LAMP stack) that supports full CRUD operations and token-based authentication using MySQL.

---

## Features

* RESTful API (GET, POST, PUT, DELETE)
* MySQL database integration
* JSON responses
* Custom routing system
* Token-based Authentication (Register & Login)
* Protected routes
* Input validation

---

## Tech Stack

* PHP (Core PHP)
* MySQL
* Apache (XAMPP / LAMP)
* Postman (for API testing)

---

## Installation & Setup

Follow these steps to run the project locally:

### 1. Clone the Repository

```bash
git clone https://github.com/tsshekwogaza/php-rest-api
cd C:\xampp\htdocs\api>
```

---

### 2. Move Project to Server Directory

If using XAMPP:

```bash
C:\xampp\htdocs\
```

If using Linux (LAMP):

```bash
/var/www/html/
```

---

### 3. Start Apache & MySQL

* Open XAMPP Control Panel
* Start:

  * Apache
  * MySQL

---

### 4. Create Database

Open phpMyAdmin and run:

```sql
CREATE DATABASE my_api;

USE my_api;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
);

ALTER TABLE users 
ADD password VARCHAR(255),
ADD token VARCHAR(255);
```

---

### 5. Configure Database Connection

Open `config.php` and update if needed:

```php
$host = "localhost";
$db   = "my_api";
$user = "root";
$pass = "";
```

---

### 6. Configure URL Routing (IMPORTANT)

Create a `.htaccess` file in your project root:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ api.php [QSA,L]
```

---

### 7. Run the Project

Open browser:

```
http://localhost/api/users
```

---

# Authentication Flow

This API uses **token-based authentication**.

---

## Register

**POST** `/api/register`

### Body:

```json
{
  "name": "Tim",
  "email": "tim@test.com",
  "password": "123456"
}
```

---

## Login

**POST** `/api/login`

### Body:

```json
{
  "email": "tim@test.com",
  "password": "123456"
}
```

### Response:

```json
{
  "message": "Login successful",
  "token": "your_token_here"
}
```

---

## Access Protected Routes

All `/api/users` endpoints require authentication.

### Add Header:

```
Authorization: Bearer your_token_here
```

---

# API Endpoints Testing (Using Postman)

### Create User (POST)

* Method: POST
* URL:

```
http://localhost/api/users
```

* Body (JSON):

```json
{
  "name": "John Doe",
  "email": "john@example.com"
}
```

---

### Get All Users (GET)

```
GET /api/users
```

---

### Get Single User

```
GET /api/users/1
```

---

### Update User (PUT)

```
PUT /api/users/1
```

Body:

```json
{
  "name": "Updated Name",
  "email": "updated@email.com"
}
```

---

### Delete User

```
DELETE /api/users/1
```

---

# Testing (Using Postman)

1. Register a user
2. Login → copy token
3. Add token to headers
4. Test protected routes

---

## Project Structure

```
/project-root
│── api.php        # Main router
│── config.php     # Database connection
│── .htaccess      # URL routing
```

---

## Notes

* Ensure Apache mod_rewrite is enabled
* Use Postman or cURL to test endpoints
* All responses are returned in JSON format
* Tokens are stored in the database

---

## Future Improvements

* JWT Authentication
* Logout system
* Token expiration
* MVC architecture

---

## Author

**Timothy Samuel Shekwogaza** | GitHub: https://github.com/tsshekwogaza
