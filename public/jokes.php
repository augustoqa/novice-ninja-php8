<?php
try {
    include __DIR__ . '/../includes/DatabaseConnection.php';

    $sql = 'SELECT `joke`.`id`, `joketext`, `name`, `email` 
            FROM `joke` INNER JOIN `author`
                ON `authorid` = `author`.`id`';
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