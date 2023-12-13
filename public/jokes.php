<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'admin');

    $sql = 'SELECT `joketext` FROM `joke`';
    $result = $pdo->query($sql);

    foreach ($result as $row) {
        $jokes[] = $row['joketext'];
    }

    $title = 'Joke list';

    // Start the buffer
    ob_start();

    // Include the template. The PHP code will be executed,
    // but the resulting HTML will be stored in the buffer
    // rather than sent to the browser.
    include __DIR__ . '/../templates/jokes.html.php';

    // Read the contents of the output buffer and store them
    // in the $output variable for use in layout.html.php
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