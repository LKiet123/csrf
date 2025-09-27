CREATE DATABASE test_db;
USE test_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    password VARCHAR(255),
    fullname VARCHAR(100),
    email VARCHAR(100),
    type VARCHAR(20)
);

INSERT INTO users (name, password, fullname, email, type) VALUES 
('admin', MD5('123456'), 'Administrator', 'admin@test.com', 'admin'),
('user1', MD5('password1'), 'User One', 'user1@test.com', 'user'),
('user2', MD5('password2'), 'User Two', 'user2@test.com', 'user');