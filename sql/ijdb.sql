CREATE DATABASE `ijdb`;

CREATE USER 'ijdbuser'@'%' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON `ijdb`.* TO 'ijdbuser'@'%';


USE `ijdb`;

CREATE TABLE `joke` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `joketext` TEXT,
    `jokedate` DATE NOT NULL
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;
