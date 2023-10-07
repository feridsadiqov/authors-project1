<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'project1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

try {
    $db = new PDO('mysql:host=' . DB_HOST .';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $db->query(' SET CHARSET utf8');
    
} catch (\PDOException $th) {
    echo $th->getMessage();
}

$sql = "
SELECT authors.id AS author_id, authors.name AS author_name, books.id AS book_id, books.title AS book_title
FROM authors
JOIN books ON authors.id = books.author_id
ORDER BY authors.id;
";
$data = $db->prepare($sql);
$data->execute();
$row=$data->fetchAll(PDO::FETCH_ASSOC);
// print_r($row);

$authors = array();

foreach ($row as $rows) {
    $author_id = $rows['author_id'];
    $author_name = $rows['author_name'];
    $book_id = $rows['book_id'];
    $book_title = $rows['book_title'];

    if (!isset($authors[$author_id])) {
        $authors[$author_id] = array(
            'author_name' => $author_name,
            'books' => array(),
            'total_books' => 0,
        );
    }

    $authors[$author_id]['books'][$book_id] = $book_title;
    $authors[$author_id]['total_books']++;
}

$json_data = json_encode($authors, JSON_PRETTY_PRINT);
echo $json_data;

