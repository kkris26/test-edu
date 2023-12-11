<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .like-button {
            padding: 5px 10px;
            cursor: pointer;
        }

        .like-button.liked {
            background-color: red;
            color: white;
        }

        .like-button.unliked {
            background-color: green;
            color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Login and Like News Example</title>
</head>

<body>
    <?php
    session_start();
    require_once('functions.php');
    require_once('config.php');

    if (isUserLoggedIn()) {
        echo "<div class='welcome-message'>Welcome, {$_SESSION['username']}!</div>";
        echo "<a href='logout.php'>Logout</a></div>";

        $userId = $_SESSION['user_id'];

        // Query untuk mengambil berita dari database
        $getNewsSql = "SELECT * FROM news";
        $newsResult = $conn->query($getNewsSql);

        if ($newsResult->num_rows > 0) {
            while ($item = $newsResult->fetch_assoc()) {
                $newsId = $item['id'];
                $likeCheckSql = "SELECT id FROM likes_news WHERE user_id = '$userId' AND news_id = '$newsId'";
                $likeCheckResult = $conn->query($likeCheckSql);
                $isLiked = ($likeCheckResult && $likeCheckResult->num_rows > 0);

                $likeCountSql = "SELECT COUNT(*) AS like_count FROM likes_news WHERE news_id = '$newsId'";
                $likeCountResult = $conn->query($likeCountSql);
                $likeCount = ($likeCountResult) ? $likeCountResult->fetch_assoc()['like_count'] : 0;

                echo "<div class='news-item'>";
                echo "<h2>{$item['title']}</h2>";
                echo "<p>{$item['content']}</p>";
                echo "<button class='like-button" . ($isLiked ? ' liked' : ' unliked') . "' data-news-id='{$newsId}'>" . ($isLiked ? 'Unlike' : 'Like') . "</button>";
                echo "<span class='like-count'>Likes: <span id='like-count-{$newsId}'>{$likeCount}</span></span>";
                echo "</div>";
            }
        } else {
            echo "<p>No news available.</p>";
        }
    } else {
    ?>
        <div class="login-form-container">
            <form action="login.php" method="post" class="login-form">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    <?php
    }
    ?>

    <script>
        $(document).ready(function() {
            $('.like-button').click(function() {
                const newsId = $(this).data('news-id');
                const button = $(this);

                $.ajax({
                    type: 'POST',
                    url: 'like_news.php',
                    data: {
                        news_id: newsId
                    },
                    success: function(response) {
                        // alert(response);

                        // Update the button text and color based on the response
                        button.text(response === 'Liked' ? 'Unlike' : 'Like');
                        button.toggleClass('liked', response === 'Liked');
                        button.toggleClass('unliked', response === 'Unliked');

                        // Update the like count
                        updateLikeCount(newsId);
                    },
                    error: function() {
                        alert('Error liking/unliking news.');
                    }
                });
            });

            function updateLikeCount(newsId) {
                const likeCountElement = $('#like-count-' + newsId);
                if (likeCountElement.length) {
                    $.ajax({
                        type: 'GET',
                        url: 'get_like_count.php',
                        data: {
                            news_id: newsId
                        },
                        success: function(likeCount) {
                            likeCountElement.text(likeCount);
                        },
                        error: function() {
                            console.error('Error getting like count.');
                        }
                    });
                }
            }
        });
    </script>
</body>

</html>