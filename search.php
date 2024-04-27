<?php

$mysqli = new mysqli('localhost', 'root', '', 'blog');

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

$search = $_GET['search'];

$stmt = $mysqli->prepare("SELECT p.title, c.body
                          FROM posts p
                          JOIN comments c ON p.id = c.post_id
                          WHERE c.body LIKE ?");
$searchParam = '%' . $search . '%';
$stmt->bind_param('s', $searchParam);
$stmt->execute();
$result = $stmt->get_result();

echo '<h2>Результаты поиска:</h2>';
while ($row = $result->fetch_assoc()) {
    echo '<h3>' . $row['title'] . '</h3>';
    echo '<p>' . $row['body'] . '</p>';
}

$stmt->close();
$mysqli->close();
