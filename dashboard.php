<?php
session_start();
require_once 'config/db.php';

// SECURITY CHECK: Kick out anyone who isn't logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// --- LOGIC: UPLOAD PROFILE PICTURE ---
if (isset($_POST['upload_image'])) {
    $image_name = $_FILES['profile_pic']['name'];
    $target_dir = "uploads/";
    
    // Create a unique name to prevent overwriting images with the same name
    $unique_image_name = time() . "_" . basename($image_name);
    $target_file = $target_dir . $unique_image_name;
    
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
        // Update the database with the new image name
        $sql = "UPDATE users SET profile_image = '$unique_image_name' WHERE id = $user_id";
        if ($conn->query($sql)) {
            $message = "<p style='color: #e5a93d; text-align:center;'>Profile picture updated successfully!</p>";
        }
    } else {
        $message = "<p style='color: red; text-align:center;'>Failed to upload image. Make sure your 'uploads' folder exists!</p>";
    }
}

// Fetch Current User Data
$user_query = $conn->query("SELECT full_name, email, profile_image, created_at FROM users WHERE id = $user_id");
$user_data = $user_query->fetch_assoc();

// Set the avatar image (Use default if they haven't uploaded one yet)
$avatar_src = ($user_data['profile_image'] != 'default.png' && $user_data['profile_image'] != '') 
              ? 'uploads/' . $user_data['profile_image'] 
              : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';

// --- NEW LOGIC: Fetch User's Reviews ---
$my_reviews_query = $conn->query("SELECT r.rating, r.review_text, m.title 
                                  FROM reviews r 
                                  JOIN movies m ON r.movie_id = m.id 
                                  WHERE r.user_id = $user_id 
                                  ORDER BY r.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dashboard | KUET Film Club</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-header { display: flex; align-items: center; gap: 30px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid #333; }
        .dashboard-avatar { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary); }
        .upload-form { background: #111; padding: 20px; border-radius: 8px; border: 1px solid #333; max-width: 400px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">KUET Film Club</div>
        <ul class="nav-links">
            <li><a href="index.php">Home View</a></li>
            <li><a href="logout.php" style="color: #ff5e5e;">Log Out</a></li>
        </ul>
    </nav>

    <section class="section" style="padding-top: 120px; min-height: 100vh;">
        <div class="container">
            <h2 class="section-title" style="text-align: left;">Member Dashboard</h2>
            <?php echo $message; ?>

            <div class="dashboard-header">
                <img src="<?php echo $avatar_src; ?>" alt="Profile Picture" class="dashboard-avatar">
                <div>
                    <h3 style="font-size: 2rem; color: #fff;"><?php echo htmlspecialchars($user_data['full_name']); ?></h3>
                    <p style="color: var(--primary); font-size: 1.1rem; margin-bottom: 5px;">Verified Club Member</p>
                    <p style="color: #888;">Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
                    <p style="color: #888;">Joined: <?php echo date('F j, Y', strtotime($user_data['created_at'])); ?></p>
                </div>
            </div>

            <div class="grid-4" style="grid-template-columns: 1fr 2fr; gap: 40px;">
                
                <div>
                    <h3 style="margin-bottom: 20px; color: var(--primary);">Profile Settings</h3>
                    <div class="upload-form">
                        <p style="margin-bottom: 15px; font-size: 0.9rem; color: #aaa;">Update your profile picture</p>
                        <form method="POST" action="dashboard.php" enctype="multipart/form-data">
                            <input type="file" name="profile_pic" accept="image/*" required style="width: 100%; padding: 10px; background: #1a1a1d; color: white; border: 1px solid #333; border-radius: 4px; margin-bottom: 15px;">
                            <button type="submit" name="upload_image" class="btn-primary" style="width: 100%; margin-top: 0;">Upload Image</button>
                        </form>
                    </div>
                </div>

                <div>
                    <h3 style="margin-bottom: 20px; color: var(--primary);">My Movie Reviews</h3>
                    
                    <?php if($my_reviews_query->num_rows > 0): ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php while($my_review = $my_reviews_query->fetch_assoc()): ?>
                                <div style="background: #111; padding: 20px; border-radius: 8px; border: 1px solid #333;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <h4 style="color: #fff;"><?php echo htmlspecialchars($my_review['title']); ?></h4>
                                        <span style="color: var(--primary); font-weight: bold;"><?php echo $my_review['rating']; ?>/10</span>
                                    </div>
                                    <p style="color: #aaa; font-size: 0.95rem; font-style: italic;">"<?php echo htmlspecialchars($my_review['review_text']); ?>"</p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div style="background: #111; padding: 40px; text-align: center; border-radius: 8px; border: 1px dashed #333;">
                            <p style="color: #666;">You haven't submitted any reviews yet.</p>
                            <p style="color: #666; font-size: 0.9rem; margin-top: 10px;">Head to the homepage and select a movie to get started!</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
</body>
</html>