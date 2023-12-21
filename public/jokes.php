<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'admin');

    $sql = 'SELECT `id`, `joketext` FROM `joke`';
    $jokes = $pdo->query($sql);

    $title = 'Joke list';

    ob_start();

    include __DIR__ . '/../templates/jokes.html.php';

    $output = ob_get_clean();
} catch (PDOException $e) {
    $error = sprintf(
        'Database error: %s in %s:%s',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    );
}

include __DIR__ . '/../templates/layout.html.php';