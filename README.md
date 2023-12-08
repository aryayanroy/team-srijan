This GitHub repository hosts the source code for the Team Srijan Club Portfolio Site, the official Formula Student Club website of BIT Mesra. The site provides a dynamic platform for administrators to seamlessly manage and showcase updates, legacy information, and other essential details. Key features include:

1. Dynamic Data Fetching: Utilizes AJAX API to fetch data dynamically, ensuring real-time updates.
2. Pagination: Enables easy navigation and organization of content through a paginated interface.
3. Mobile Responsiveness: Ensures optimal viewing experience across a variety of devices for on-the-go access.
4. Admin Privileges: Administrators have the capability to appoint and remove other admins, maintaining control over site management.



Execute the following queries within the MySQL database:

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

CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    image INT(10) NOT NULL,
    overview VARCHAR(255)
);

CREATE TABLE updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT(10) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    body VARCHAR(1000) NOT NULL,
    link VARCHAR(255) NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT(10) NOT NULL UNIQUE
);

CREATE TABLE sponsors(
    id INT AUTO_INCREMENT PRIMARY KEY,
    tier TINYINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    image INT(10) NOT NULL,
    link VARCHAR(255) NOT NULL,
    description VARCHAR(1000) NOT NULL
);

CREATE TABLE milestones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT(10) NOT NULL UNIQUE,
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
    image INT(10) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    overview VARCHAR(1000) NOT NULL,
    link VARCHAR(255) NOT NULL
);

CREATE TABLE history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT(10) NOT NULL UNIQUE,
    year INT(4) NOT NULL UNIQUE,
    overview VARCHAR(1000) NOT NULL
);

CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE crews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image INT(10) NOT NULL UNIQUE,
    year INT(4) NOT NULL,
    name VARCHAR(255) NOT NULL,
    team INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    link VARCHAR(255),
    FOREIGN KEY (team) REFERENCES teams(id) ON DELETE CASCADE
);