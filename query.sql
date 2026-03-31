CREATE DATABASE my_api;

USE my_api;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price INT
);

ALTER TABLE users 
ADD password VARCHAR(255);
ADD token VARCHAR(255);