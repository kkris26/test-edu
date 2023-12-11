<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $newsId = $_GET['news_id'];
    $likeCountSql = "SELECT COUNT(*) AS like_count FROM likes_news WHERE news_id = '$newsId'";
    $likeCountResult = $conn->query($likeCountSql);
    $likeCount = ($likeCountResult) ? $likeCountResult->fetch_assoc()['like_count'] : 0;

    echo $likeCount;
} else {
    echo "Invalid request.";
}
