DROP DATABASE messanger;
CREATE DATABASE messanger;
USE messanger;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25) NOT NULL UNIQUE,
    password VARCHAR(25) NOT NULL,
    email VARCHAR(25) NOT NULL UNIQUE,
    bio VARCHAR(255),
    picture VARCHAR(255) DEFAULT 'man.png',
    date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25) NOT NULL UNIQUE,
    password VARCHAR(25) NOT NULL,
    email VARCHAR(25) NOT NULL UNIQUE,
    date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(255),
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    sender INT,
	receiver INT,
    FOREIGN KEY(sender) REFERENCES user(id),
    FOREIGN KEY(receiver) REFERENCES user(id)
);

CREATE TABLE friendship (
    id INT AUTO_INCREMENT PRIMARY KEY,
    initiator INT,
    acceptor INT,
    FOREIGN KEY(initiator) REFERENCES user(id),
    FOREIGN KEY(acceptor) REFERENCES user(id)
); -- an association of two users