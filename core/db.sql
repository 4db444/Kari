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
    address VARCHAR(255) NOT NULL,
    total_rooms INT NOT NULL,
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

DROP TABLE IF EXISTS reservations;
CREATE TABLE IF NOT EXISTS reservation(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    house_id INT NOT NULL,
    `from` DATE NOT NULL,
    `to` DATE NOT NULL,
    is_canceled BOOLEAN NOT NULL DEFAULT 0,
    rating INT CHECK (rating >= 1 AND rating <= 5) DEFAULT NULL,

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

select * from reservation;

insert into reservation values (null, 5, 1, '2026-01-01', '2026-01-10', 4);
insert into reservation values (null, 5, 1, '2026-01-05', '2026-01-15', 4);

insert into houses values(null, 'xx', 44, 44, 44, 5);
select * from houses;
select CURRENT_DATE;

select * from reservation;

select count (*)
from reservation
where house_id = 1 
and ((`to` BETWEEN "2026-01-19" and "2026-01-20") or (`from` between "2026-01-19" and "2026-01-20"));