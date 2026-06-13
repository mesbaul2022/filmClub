<?php
session_start();
require_once 'config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, full_name, password, role, is_approved FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            
            // Check if the user is approved by an admin
            if ($user['is_approved'] == 1 || $user['role'] == 'admin') {
                // Set Session Variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $message = "<p style='color: orange; text-align:center;'>Your account is pending Admin approval.</p>";
            }
        } else {
            $message = "<p style='color: red; text-align:center;'>Incorrect password.</p>";
        }
    } else {
        $message = "<p style='color: red; text-align:center;'>No account found with that email.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | KUET Film Club</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">KUET Film Club</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="register.php" style="color: var(--primary);">Register</a></li>
        </ul>
    </nav>

    <section class="section" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; margin-top: 50px;">
        <div class="profile-container" style="width: 100%; max-width: 500px;">
            <h2 class="section-title" style="margin-bottom: 20px;">Member Login</h2>
            
            <?php echo $message; ?>

            <form method="POST" action="login.php" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="email" name="email" placeholder="Email Address" required style="padding: 12px; background: #111; color: white; border: 1px solid #333; border-radius: 4px;">
                <input type="password" name="password" placeholder="Password" required style="padding: 12px; background: #111; color: white; border: 1px solid #333; border-radius: 4px;">
                <button type="submit" class="btn-primary">Secure Login</button>
            </form>
        </div>
    </section>
</body>
</html>