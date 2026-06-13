<?php
session_start();
require_once 'config/db.php';

// Check if a movie ID was passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$movie_id = (int)$_GET['id'];
$message = '';

// --- LOGIC: SUBMIT A REVIEW ---
if (isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $message = "<p style='color: red; text-align:center;'>You must be logged in to leave a review!</p>";
    } else {
        $user_id = $_SESSION['user_id'];
        $rating = (int)$_POST['rating'];
        $review_text = $conn->real_escape_string($_POST['review_text']);
        
        // Check if user already reviewed this movie
        $check_review = $conn->query("SELECT id FROM reviews WHERE movie_id = $movie_id AND user_id = $user_id");
        if ($check_review->num_rows > 0) {
            $message = "<p style='color: orange; text-align:center;'>You have already reviewed this movie.</p>";
        } else {
            $sql = "INSERT INTO reviews (movie_id, user_id, rating, review_text) VALUES ($movie_id, $user_id, $rating, '$review_text')";
            if ($conn->query($sql)) {
                $message = "<p style='color: #e5a93d; text-align:center;'>Review posted successfully!</p>";
            }
        }
    }
}

// Fetch Movie Details
$movie_query = $conn->query("SELECT * FROM movies WHERE id = $movie_id");
if ($movie_query->num_rows == 0) {
    echo "<h2 style='color:white; text-align:center; margin-top:50px;'>Movie not found.</h2>";
    exit();
}
$movie = $movie_query->fetch_assoc();

// Fetch All Community Reviews for this movie
$reviews_query = $conn->query("SELECT r.rating, r.review_text, r.created_at, u.full_name, u.profile_image 
                               FROM reviews r 
                               JOIN users u ON r.user_id = u.id 
                               WHERE r.movie_id = $movie_id 
                               ORDER BY r.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($movie['title']); ?> | KUET Film Club</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .movie-header { display: flex; gap: 40px; margin-bottom: 50px; background: #111; padding: 40px; border-radius: 8px; border: 1px solid #333; }
        .movie-header img { width: 300px; border-radius: 8px; box-shadow: var(--card-shadow); }
        .review-box { background: #1a1a1d; padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 20px; display: flex; gap: 20px; }
        .reviewer-avatar { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary); }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">KUET Film Club</div>
        <ul class="nav-links">
            <li><a href="index.php">Back to Home</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo ($_SESSION['user_role'] == 'admin') ? 'admin.php' : 'dashboard.php'; ?>" style="color: var(--primary);">Dashboard</a></li>
            <?php else: ?>
                <li><a href="login.php" style="color: var(--primary);">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section class="section" style="padding-top: 120px; min-height: 100vh;">
        <div class="container">
            <?php echo $message; ?>
            
            <div class="movie-header">
                <img src="uploads/<?php echo htmlspecialchars($movie['poster_image']); ?>" alt="Poster">
                <div>
                    <h1 style="font-size: 3rem; color: var(--primary); margin-bottom: 10px;"><?php echo htmlspecialchars($movie['title']); ?></h1>
                    <span style="display: inline-block; padding: 5px 15px; background: #222; border-radius: 20px; font-size: 0.9rem; margin-bottom: 20px;"><?php echo htmlspecialchars($movie['genre']); ?></span>
                    <p style="color: #ccc; font-size: 1.1rem; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                </div>
            </div>

            <div class="grid-4" style="grid-template-columns: 1fr 2fr; gap: 40px;">
                
                <div>
                    <h3 style="color: var(--primary); margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">Leave a Rating</h3>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <form method="POST" action="movie.php?id=<?php echo $movie_id; ?>" style="background: #111; padding: 20px; border-radius: 8px; border: 1px solid #333;">
                            <label style="color: #aaa; font-size: 0.9rem;">Your Score (1-10):</label>
                            <input type="number" name="rating" min="1" max="10" required style="width: 100%; padding: 10px; background: #1a1a1d; color: white; border: 1px solid #333; border-radius: 4px; margin: 10px 0 20px;">
                            
                            <label style="color: #aaa; font-size: 0.9rem;">Your Thoughts:</label>
                            <textarea name="review_text" rows="5" required style="width: 100%; padding: 10px; background: #1a1a1d; color: white; border: 1px solid #333; border-radius: 4px; margin: 10px 0 20px; font-family: inherit;"></textarea>
                            
                            <button type="submit" name="submit_review" class="btn-primary" style="width: 100%; margin-top: 0;">Submit Review</button>
                        </form>
                    <?php else: ?>
                        <div style="background: #111; padding: 30px; text-align: center; border-radius: 8px; border: 1px dashed #333;">
                            <p style="color: #aaa; margin-bottom: 15px;">You must be logged in to join the discussion.</p>
                            <a href="login.php" class="btn-primary">Login Now</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <h3 style="color: var(--primary); margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">Community Responses</h3>
                    
                    <?php if($reviews_query->num_rows > 0): ?>
                        <?php while($review = $reviews_query->fetch_assoc()): ?>
                            <?php 
                                // Setup avatar properly
                                $avatar = ($review['profile_image'] != 'default.png' && $review['profile_image'] != '') 
                                          ? 'uploads/' . $review['profile_image'] 
                                          : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';
                            ?>
                            <div class="review-box">
                                <img src="<?php echo $avatar; ?>" alt="Avatar" class="reviewer-avatar">
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <h4 style="color: #fff;"><?php echo htmlspecialchars($review['full_name']); ?></h4>
                                        <span style="color: var(--primary); font-weight: bold; font-size: 1.1rem;">Score: <?php echo $review['rating']; ?>/10</span>
                                    </div>
                                    <p style="color: #ccc; font-size: 0.95rem; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                    <p style="color: #666; font-size: 0.8rem; margin-top: 15px; text-align: right;"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #888; padding: 20px; background: #111; border-radius: 8px;">No community responses yet. Be the first to review!</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
</body>
</html>