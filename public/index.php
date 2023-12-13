<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'admin');

    $sql = 'SELECT `joketext` FROM `joke`';
    $result = $pdo->query($sql);

   foreach ($result as $row) {
        $jokes[] = $row['joketext'];
   }
} catch (PDOException $e) {
    $error = sprintf(
        'Database error: %s in %s:%s',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}

include __DIR__ . '/../templates/jokes.html.php';