<?php

$mysqli = new mysqli('localhost', 'root', '', 'blog');

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

function fetch_data($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

$postsData = fetch_data('https://jsonplaceholder.typicode.com/posts');
foreach ($postsData as $postData) {
    $title = $mysqli->real_escape_string($postData['title']);
    $body = $mysqli->real_escape_string($postData['body']);
    $mysqli->query("INSERT INTO posts (title, body) VALUES ('$title', '$body')");
}

$commentsData = fetch_data('https://jsonplaceholder.typicode.com/comments');
foreach ($commentsData as $commentData) {
    $postId = $commentData['postId'];
    $name = $mysqli->real_escape_string($commentData['name']);
    $email = $mysqli->real_escape_string($commentData['email']);
    $body = $mysqli->real_escape_string($commentData['body']);
    $mysqli->query("INSERT INTO comments (post_id, name, email, body) VALUES ('$postId', '$name', '$email', '$body')");
}

echo 'Загружено ' . count($postsData) . ' записей и ' . count($commentsData) . ' комментариев';

$mysqli->close();
