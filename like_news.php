<?php
session_start();
require_once('config.php');
require_once('functions.php');

if (isUserLoggedIn()) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newsId = $_POST['news_id'];
        $userId = $_SESSION['user_id'];

        // Check if the user has already liked the news
        $checkLikedSql = "SELECT id FROM likes_news WHERE user_id = '$userId' AND news_id = '$newsId'";
        $result = $conn->query($checkLikedSql);

        if ($result->num_rows === 0) {
            // If not liked, insert the like
            $insertLikeSql = "INSERT INTO likes_news (user_id, news_id) VALUES ('$userId', '$newsId')";
            $conn->query($insertLikeSql);

            echo "Liked";
        } else {
            // If already liked, remove the like
            $removeLikeSql = "DELETE FROM likes_news WHERE user_id = '$userId' AND news_id = '$newsId'";
            $conn->query($removeLikeSql);

            echo "Unliked";
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "User not logged in";
};