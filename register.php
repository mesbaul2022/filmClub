<?php
// Start session and connect to database
session_start();
require_once 'config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $message = "<p style='color: red; text-align:center;'>This email is already registered!</p>";
    } else {
        // Insert new user into database (Default role is 'user', is_approved is 0/Pending)
        $sql = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "<p style='color: #e5a93d; text-align:center;'>Registration successful! Please wait for Admin approval to login.</p>";
        } else {
            $message = "<p style='color: red; text-align:center;'>Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | KUET Film Club</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">KUET Film Club</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php" style="color: var(--primary);">Login</a></li>
        </ul>
    </nav>

    <section class="section" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; margin-top: 50px;">
        <div class="profile-container" style="width: 100%; max-width: 500px;">
            <h2 class="section-title" style="margin-bottom: 20px;">Join the Club</h2>
            
            <?php echo $message; ?>

            <form method="POST" action="register.php" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="text" name="full_name" placeholder="Full Name" required style="padding: 12px; background: #111; color: white; border: 1px solid #333; border-radius: 4px;">
                <input type="email" name="email" placeholder="Student Email (@stud.kuet.ac.bd)" required style="padding: 12px; background: #111; color: white; border: 1px solid #333; border-radius: 4px;">
                <input type="password" name="password" placeholder="Create Password" required style="padding: 12px; background: #111; color: white; border: 1px solid #333; border-radius: 4px;">
                <button type="submit" class="btn-primary">Submit Application</button>
            </form>
            <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">Already applied? <a href="login.php" style="color: var(--primary);">Login here</a></p>
        </div>
    </section>
</body>
</html>