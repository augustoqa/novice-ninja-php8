<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'admin');

    $sql = "UPDATE `joke` SET `jokedate`='2021-04-01' WHERE `joketext` LIKE '%programmer%'";
    $affectedRows = $pdo->exec($sql);

    $output = "Updated {$affectedRows} row(s)";
} catch (PDOException $e) {
    $output = sprintf(
        'Database error: %s in %s:%s',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}

include __DIR__ . '/../templates/output.html.php';