# formula-student
This is an interactive website for EV formula student club, "Team Srijan" - BIT Mesra

CREATE DATABASE teamsrijan;

USE teamsrijan;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    parent INT NOT NULL,
    FOREIGN KEY (parent) REFERENCES admins(id) ON DELETE CASCADE
);

INSERT INTO admins (name, email, password, parent) VALUES ("Default User", "btech@bitmesra.ac.in", "$2y$10$s./JsrwOze5ROHmzliDvgeSWyyqKDM/CyuQ80AfA9hssT.kw5I1J6", 1);

CREATE TABLE updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    body VARCHAR(1000) NOT NULL,
    link VARCHAR(255) NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT NOT NULL UNIQUE
);

CREATE TABLE sponsors(
    id INT AUTO_INCREMENT PRIMARY KEY,
    tier TINYINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    image INT NOT NULL,
    link VARCHAR(255) NOT NULL,
    description VARCHAR(1000) NOT NULL
);

CREATE TABLE milestones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    body VARCHAR(1000) NOT NULL,
    link VARCHAR(255) NOT NULL
);

CREATE TABLE garage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    images JSON NOT NULL,
    name VARCHAR(255) NOT NULL,
    overview VARCHAR(1000) NOT NULL,
    specs JSON NOT NULL,
    achievements VARCHAR(1000) NOT NULL
);

CREATE TABLE competitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    overview VARCHAR(1000) NOT NULL,
    link VARCHAR(255) NOT NULL
);

CREATE TABLE history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT NOT NULL UNIQUE,
    year INT NOT NULL UNIQUE,
    overview VARCHAR(1000) NOT NULL
);

CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE crews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT NOT NULL UNIQUE,
    year TINYINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    team INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    link VARCHAR(255),
    FOREIGN KEY (team) REFERENCES teams(id) ON DELETE CASCADE
);