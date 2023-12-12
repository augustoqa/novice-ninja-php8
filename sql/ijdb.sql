CREATE DATABASE `ijdb`;

CREATE USER 'ijdbuser'@'%' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON `ijdb`.* TO 'ijdbuser'@'%';