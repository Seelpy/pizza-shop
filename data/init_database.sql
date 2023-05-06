CREATE DATABASE php_pizza_shop;

USE php_pizza_shop;

CREATE TABLE user
(
  email VARCHAR(200) NOT NULL,
  password VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  lastname VARCHAR(100),
  address VARCHAR(300),
  avatar_path VARCHAR(100),
  PRIMARY KEY (email)
);

CREATE TABLE pizza
(
  id INT UNSIGNED AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(100),
  category VARCHAR(100),
  price DECIMAL(8, 2) NOT NULL,
  img_path VARCHAR(100),
  PRIMARY KEY (id)
);