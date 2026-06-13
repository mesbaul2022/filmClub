<?php
session_start();
require_once 'config/db.php';

// Check if a film was clicked
if (!isset($_GET['film']) || empty($_GET['film'])) {
    header("Location: index.php");
    exit();
}

$film_id = $_GET['film'];

// Database of KUET Originals 
$originals_db = [
    'shajghor' => [
        'title' => 'Shajghor',
        'genre' => 'Short Film',
        'youtubeId' => 'nNx92w_oz_s',
        'poster' => 'images/shajghor.jpg',
        'details' => "<strong>Directed by:</strong> Pavel (KUET URP'16)<br><strong>Screenplay:</strong> Anika (KUET URP'16)<br><strong>Assistant Director:</strong> Swadhin (KUET URP'16)<br><br><strong>Cast & Voice:</strong> Anika"
    ],
    'debi' => [
        'title' => 'Debi',
        'genre' => 'Music Video',
        'youtubeId' => '3phdyG_IR6A',
        'poster' => 'images/debi.jpg',
        'details' => "Created for the cultural night of Odoito '13 (KUET 2k13).<br><br><strong>Direction:</strong> Sohan<br><strong>Cinematography:</strong> Proshonul Haque Rafa & Shadman Rahman Doha<br><strong>Cast:</strong> Sohan, George & Preema<br><strong>Special Appearance:</strong> Faria & Shoshi"
    ],
    'satyajit' => [
        'title' => 'A Tribute to Satyajit Ray',
        'genre' => 'Documentary / Tribute',
        'youtubeId' => 'm5IbB_9FKW0',
        'poster' => 'images/satyajit.jpg',
        'details' => "A tribute presented by KUET Film Society. This video was officially premiered at the Satyajit Cholochitro Utshob '2017 at the KUET Auditorium."
    ],
    'perfect' => [
        'title' => 'Perfect || ED Sheeran Cover',
        'genre' => 'Music Video Cover',
        'youtubeId' => 'X-OEwuWxBTE',
        'poster' => 'images/perfect.jpg',
        'details' => "A beautiful cinematic tribute to the song 'Perfect' from the students of KUET.<br><br><strong>Direction, Cinematography & Editing:</strong> Mrinmoy Roy<br><strong>Cast:</strong> Muntaseer Rahman & Tabassum Islam Sreya<br><strong>Promotional Partner:</strong> KUET Film Society"
    ]
];

// Check if the URL matches one of our films
if (!array_key_exists($film_id, $originals_db)) {
    echo "<h2 style='color:white; text-align:center; margin-top:50px;'>Original content not found.</h2>";
    exit();
}

$film = $originals_db[$film_id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $film['title']; ?> | KUET Originals</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .movie-header { display: flex; gap: 40px; margin-bottom: 50px; background: #111; padding: 40px; border-radius: 8px; border: 1px solid #333; }
        .movie-header img { width: 300px; height: 450px; border-radius: 8px; box-shadow: var(--card-shadow); object-fit: cover;}
        .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.8); margin-bottom: 50px; border: 1px solid var(--primary);}
        .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
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
            
            <div class="movie-header">
                <img src="<?php echo $film['poster']; ?>" alt="Cover Image">
                <div style="flex: 1;">
                    <h1 style="font-size: 3.5rem; color: var(--primary); margin-bottom: 10px;"><?php echo $film['title']; ?></h1>
                    <span style="display: inline-block; padding: 5px 15px; background: #222; border-radius: 20px; font-size: 0.9rem; margin-bottom: 30px;"><?php echo $film['genre']; ?></span>
                    
                    <div style="padding: 25px; background: #1a1a1d; border-radius: 8px; border-left: 4px solid var(--primary);">
                        <h4 style="color: #fff; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">Production Information</h4>
                        <p style="color: #ccc; font-size: 1.1rem; line-height: 1.8;"><?php echo $film['details']; ?></p>
                    </div>
                </div>
            </div>

            <h3 style="color: var(--primary); margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px; text-transform: uppercase; letter-spacing: 2px;">Watch Content</h3>
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/<?php echo $film['youtubeId']; ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

        </div>
    </section>
</body>
</html>