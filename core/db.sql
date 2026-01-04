DROP DATABASE IF EXISTS kari;
CREATE DATABASE IF NOT EXISTS kari;
USE kari;

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS houses;
CREATE TABLE IF NOT EXISTS houses(
    id INT PRIMARY KEY AUTO_INCREMENT,
    city VARCHAR(50) NOT NULL,
    room_number INT NOT NULL,
    max_guests INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    owner INT NOT NULL,

    FOREIGN KEY (owner)
        REFERENCES users(id)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS images;
CREATE TABLE IF NOT EXISTS images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    src VARCHAR(255) NOT NULL,
    house_id INT NOT NULL,

    FOREIGN KEY (house_id)
        REFERENCES houses(id)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS reservation;
CREATE TABLE IF NOT EXISTS reservation(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    house_id INT NOT NULL,
    `from` DATE NOT NULL,
    `to` DATE NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,
    FOREIGN KEY (house_id)
        REFERENCES houses(id)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS favorites;
CREATE TABLE IF NOT EXISTS favorites(
    user_id INT NOT NULL,
    house_id INT NOT NULL,
    PRIMARY KEY (user_id, house_id),

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,
    FOREIGN KEY (house_id)
        REFERENCES  houses(id)
        ON DELETE CASCADE
);