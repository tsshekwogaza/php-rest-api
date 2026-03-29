# PHP REST API

A simple REST API built with PHP (LAMP stack) that supports full CRUD operations using MySQL.

---

## Features

* RESTful API (GET, POST, PUT, DELETE)
* MySQL database integration
* JSON responses
* Custom routing system
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
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git
cd YOUR_REPO
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
```

---

### 5. Configure Database Connection

Open `db.php` and update if needed:

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
http://localhost/YOUR_PROJECT_FOLDER/api/users
```

---

## API Testing (Using Postman)

### Create User (POST)

* Method: POST
* URL:

```
http://localhost/YOUR_PROJECT_FOLDER/api/users
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

## Project Structure

```
/project-root
│── api.php        # Main router
│── db.php         # Database connection
│── .htaccess      # URL routing
```

---

## Notes

* Ensure Apache mod_rewrite is enabled
* Use Postman or cURL to test endpoints
* All responses are returned in JSON format

---

## Future Improvements

* Authentication (JWT)
* Input sanitization
* Error handling with HTTP status codes
* MVC structure

---

## Author

Your Name
GitHub: https://github.com/YOUR_USERNAME
