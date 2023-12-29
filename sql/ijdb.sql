CREATE DATABASE `ijdb`;

CREATE USER 'ijdbuser'@'%' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON `ijdb`.* TO 'ijdbuser'@'%';


USE `ijdb`;

CREATE TABLE `joke` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `joketext` TEXT,
    `jokedate` DATE NOT NULL
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;


INSERT INTO  `joke`
SET `joketext` = 'A programmer was found dead in the shower. The instructions read: lather, rinse, repeat.',
    `jokedate` = '2021-10-29';


# Add column to the joke table
ALTER TABLE `joke` ADD COLUMN `authorid` INT;

# Create 'author' table
CREATE TABLE `author` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255),
    `email` VARCHAR(255)
) DEFAULT CHARACTER SET utf8 ENGINE=InnoDB;

# Drop all rows from the 'joke' table
TRUNCATE TABLE `joke`;

# Adding authors to the database
# We specify the IDs so they're known when we add the jokes below.

INSERT INTO `author` SET
  `id` = 1,
  `name` = 'Kevin Yank',
  `email` = 'thatguy@kevinyank.com';

INSERT INTO `author` (`id`, `name`, `email`)
VALUES (2, 'Tom Butler', 'tom@r.je');

# Adding jokes to the database

INSERT INTO `joke` SET
  `joketext` = 'How many programmers does it take to screw in a lightbulb? None, it\'s a hardware problem.',
  `jokedate` = '2021-04-01',
  `authorid` = 1;

INSERT INTO `joke` (`joketext`, `jokedate`, `authorid`)
VALUES (
  'Why did the programmer quit his job? He didn\'t get arrays',
  '2021-04-01',
  1
);

INSERT INTO `joke` (`joketext`, `jokedate`, `authorid`)
VALUES (
  'Why was the empty array stuck outside? It didn\'t have any keys',
  '2021-04-01',
  2
);