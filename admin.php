<?php
session_start();
require_once 'config/db.php';

// SECURITY CHECK: Kick out anyone who isn't logged in OR isn't an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';

// --- LOGIC 1: APPROVE PENDING USERS ---
if (isset($_POST['approve_user'])) {
    $user_id = (int)$_POST['user_id'];
    $conn->query("UPDATE users SET is_approved = 1 WHERE id = $user_id");
    $message = "<p style='color: #e5a93d; text-align:center;'>Member approved successfully!</p>";
}

// --- LOGIC 2: REMOVE USER ---
if (isset($_POST['remove_user'])) {
    $user_id = (int)$_POST['user_id'];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    $message = "<p style='color: red; text-align:center;'>Member removed from roster.</p>";
}

// --- LOGIC 3: ADD NEW MOVIE ---
if (isset($_POST['add_movie'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $description = $conn->real_escape_string($_POST['description']);
    $admin_id = $_SESSION['user_id'];

    // Handle File Upload
    $poster_name = $_FILES['poster_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($poster_name);
    
    if (move_uploaded_file($_FILES['poster_image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO movies (title, genre, description, poster_image, added_by) VALUES ('$title', '$genre', '$description', '$poster_name', '$admin_id')";
        if ($conn->query($sql)) {
            $message = "<p style='color: #e5a93d; text-align:center;'>Movie added to database successfully!</p>";
        }
    } else {
        $message = "<p style='color: red; text-align:center;'>Failed to upload poster image.</p>";
    }
}

// --- LOGIC 4: SCHEDULE NEW EVENT ---
if (isset($_POST['add_event'])) {
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $duration = (int)$_POST['duration'];
    $venue = $conn->real_escape_string($_POST['venue']);
    
    // Handle File Upload for Banner
    $banner_name = time() . "_" . $_FILES['event_banner']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($banner_name);
    
    if (move_uploaded_file($_FILES['event_banner']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO events (event_name, event_date, duration, venue, banner_image, details) 
                VALUES ('$event_name', '$event_date', $duration, '$venue', '$banner_name', 'No extra details provided.')";
        if ($conn->query($sql)) {
            $message = "<p style='color: #e5a93d; text-align:center;'>Event successfully scheduled and deployed!</p>";
        }
    } else {
        $message = "<p style='color: red; text-align:center;'>Failed to upload event banner.</p>";
    }
}

// FETCH DATA FOR TABLES
$pending_users = $conn->query("SELECT id, full_name, email FROM users WHERE is_approved = 0");
$approved_users = $conn->query("SELECT id, full_name, role FROM users WHERE is_approved = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | KUET Film Club</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">KUET Film Club Admin</div>
        <ul class="nav-links">
            <li><a href="index.php">Home View</a></li>
            <li><a href="logout.php" style="color: red;">Log Out</a></li>
        </ul>
    </nav>

    <section class="section" style="padding-top: 120px;">
        <div class="container">
            <h2 class="section-title">Command Center Matrix</h2>
            <?php echo $message; ?>

            <div class="grid-4" style="grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 50px;">
                
                <div class="card" style="text-align: left; background: #111;">
                    <h3 style="color: var(--primary); margin-bottom: 20px;">Initialize Review Thread (Add Movie)</h3>
                    <form method="POST" action="admin.php" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                        <input type="text" name="title" placeholder="Movie Title" required class="form-input">
                        
                        <div style="display: flex; gap: 15px;">
                            <input type="text" name="genre" placeholder="Genre (e.g., Sci-Fi, Drama)" required class="form-input" style="flex: 1;">
                        </div>

                        <label style="color: #aaa; font-size: 0.9rem;">Movie Thumbnail Graphic:</label>
                        <input type="file" name="poster_image" accept="image/*" required class="form-input" style="padding: 5px;">

                        <textarea name="description" placeholder="Write full movie details here..." rows="4" required class="form-input"></textarea>
                        
                        <button type="submit" name="add_movie" class="btn-primary">Broadcast Thread</button>
                    </form>
                </div>

                <div class="card" style="text-align: left; background: #111;">
                    <h3 style="color: var(--primary); margin-bottom: 20px;">Schedule New Event</h3>
                    <form method="POST" action="admin.php" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                        <input type="text" name="event_name" placeholder="Event Name" required class="form-input">
                        
                        <div style="display: flex; gap: 15px;">
                            <input type="datetime-local" name="event_date" required class="form-input" style="flex: 1;">
                            <input type="number" name="duration" placeholder="Duration (Hrs)" required class="form-input" style="width: 120px;">
                        </div>

                        <input type="text" name="venue" placeholder="Venue Location (e.g., KUET Auditorium)" required class="form-input">
                        
                        <label style="color: #aaa; font-size: 0.9rem;">Event Banner Image:</label>
                        <input type="file" name="event_banner" accept="image/*" class="form-input" style="padding: 5px;">

                        <button type="submit" name="add_event" class="btn-primary">Deploy Event</button>
                    </form>
                </div>
            </div>

            <h3 style="color: #ff5e5e; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">Pending Access Applications</h3>
            <table class="admin-table" style="margin-bottom: 50px;">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php if($pending_users->num_rows > 0): ?>
                    <?php while($row = $pending_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <form method="POST" action="admin.php">
                                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="approve_user" class="btn-primary" style="padding: 5px 15px; margin: 0; font-size: 0.8rem;">Approve</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align: center; color: #888;">No outstanding entry permissions requested.</td></tr>
                <?php endif; ?>
            </table>

            <h3 style="color: #ff5e5e; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">Member Roster</h3>
            <table class="admin-table">
                <tr>
                    <th>Account Holder</th>
                    <th>Classification Tier</th>
                    <th>Administrative Action</th>
                </tr>
                <?php while($row = $approved_users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><span style="color: var(--primary);"><?php echo strtoupper($row['role']); ?></span></td>
                    <td>
                        <?php if($row['role'] !== 'admin'): ?>
                        <form method="POST" action="admin.php">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="remove_user" class="btn-primary" style="background: transparent; color: #ff5e5e; border: 1px solid #ff5e5e; padding: 5px 15px; margin: 0; font-size: 0.8rem;">Remove Member</button>
                        </form>
                        <?php else: ?>
                            <span style="color: #888;">Locked (Admin)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        </div>
    </section>
</body>
</html>