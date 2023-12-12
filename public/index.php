<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'admin');

    $insertRows = 0;
    $sql = "INSERT INTO  `joke` 
        SET `joketext` = 'A programmer was found dead in the shower. The instructions read: lather, rinse, repeat.', 
        `jokedate` = '2021-10-29'";

    $insertRows = $pdo->exec($sql);

    $sql2 = "INSERT INTO `joke`(`joketext`, `jokedate`)
        VALUES('A programmer was found dead in the shower. The instructions read: lather, rinse, repeat.', '2021-10-29')";


    $insertRows += $pdo->exec($sql2);

    $output = "Inserted {$insertRows} row(s) to the database";
} catch (PDOException $e) {
    $output = sprintf(
        'Database error: %s in %s:%s',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}

include __DIR__ . '/../templates/output.html.php';