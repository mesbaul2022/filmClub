<?php
session_start();
require_once 'config/db.php';

// Fetch the latest movies added by the Admin
$movies_query = "SELECT * FROM movies ORDER BY created_at DESC LIMIT 8";
$movies_result = $conn->query($movies_query);
// Fetch upcoming events
$events_query = "SELECT * FROM events ORDER BY event_date ASC LIMIT 3";
$events_result = $conn->query($events_query);

// Fetch logged-in user's profile preview data
$profile_data = null;
$latest_review = null;
$review_count = 0;

if (isset($_SESSION['user_id'])) {
    $active_user_id = $_SESSION['user_id'];
    
    // Get user details
    $user_query = $conn->query("SELECT full_name, created_at, profile_image FROM users WHERE id = $active_user_id");
    if ($user_query && $user_query->num_rows > 0) {
        $profile_data = $user_query->fetch_assoc();
    }

    // Get their single most recent review
    $review_query = $conn->query("SELECT r.rating, r.review_text, m.title FROM reviews r JOIN movies m ON r.movie_id = m.id WHERE r.user_id = $active_user_id ORDER BY r.created_at DESC LIMIT 1");
    if ($review_query && $review_query->num_rows > 0) {
        $latest_review = $review_query->fetch_assoc();
    }

    // Count their total reviews
    $count_query = $conn->query("SELECT COUNT(*) as total FROM reviews WHERE user_id = $active_user_id");
    if ($count_query) {
        $review_count = $count_query->fetch_assoc()['total'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KUET Film Club | Premium Movie Experience</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">KUET Film Club</div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#activities">Activities</a></li>
            <li><a href="#movies">Movies</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <li><a href="admin.php" style="color: var(--primary); font-weight: bold;">Command Center</a></li>
                <?php else: ?>
                    <li><a href="dashboard.php" style="color: var(--primary); font-weight: bold;">My Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php" style="color: #ff5e5e;">Logout</a></li>
                
            <?php else: ?>
                <li><a href="login.php" style="color: var(--primary);">Login</a></li>
                <li><a href="register.php" class="btn-primary" style="padding: 8px 15px; margin-top: 0; color: #000;">Join Us</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Experience Cinema Together</h1>
            <p>Welcome to the premier community for cinephiles at Khulna University of Engineering & Technology.</p>
            
            <a href="register.php" class="btn-primary" style="font-size: 1.1rem; padding: 15px 35px; text-decoration: none; display: inline-block;">Become a Member</a>
            
        </div>
    </section>

    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title">The KUET Cinema Culture</h2>
            <div style="max-width: 800px; margin: 0 auto; text-align: center; color: var(--text-muted); font-size: 1.1rem;">
                <p style="margin-bottom: 15px;">We are a collective of cinephiles, student directors, and critics dedicated to the art of visual storytelling. From dissecting cinematography to launching student filmmakers, we bridge the gap between engineering and the cinematic arts.</p>
            </div>
        </div>
    </section>

    <section id="activities" class="section bg-darker">
        <div class="container">
            <h2 class="section-title">Core Activities</h2>
            <div class="grid-4">
                <div class="card" onclick="openDedicatedGallery('screenings')">
                    <h3 style="color: var(--primary); margin-bottom: 10px;">Cinematic Screenings</h3>
                    <p>Experience campus premieres of massive hits like 'Haoa' and 'Doob' right here in the KUET auditorium.</p>
                </div>
                
                <div class="card" onclick="openDedicatedGallery('podcasts')">
                    <h3 style="color: var(--primary); margin-bottom: 10px;">Podcasts & Discussions</h3>
                    <p>Deep dive into filmmaking through interactive sessions and dedicated podcasts like 'Humayun Adda'.</p>
                </div>
                
                <div class="card" onclick="openDedicatedGallery('originals')">
                    <h3 style="color: var(--primary); margin-bottom: 10px;">Originals & Contests</h3>
                    <p>Showcasing student talent through the Intra-KUET Short Film Contest and exclusive original premieres.</p>
                </div>
                
                <div class="card" onclick="openDedicatedGallery('fairs')">
                    <h3 style="color: var(--primary); margin-bottom: 10px;">Festivals & Recognitions</h3>
                    <p>From energetic Club Fairs to participating in external film recognitions like Operation Sundarban.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="events" class="section bg-darker">
        <div class="container">
            <h2 class="section-title">Upcoming Club Events</h2>
            
            <div class="grid-4" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                <?php if ($events_result && $events_result->num_rows > 0): ?>
                    <?php while ($event = $events_result->fetch_assoc()): ?>
                        <div class="card" style="padding: 0; overflow: hidden; border: 1px solid #333; text-align: left;">
                            <img src="uploads/<?php echo htmlspecialchars($event['banner_image']); ?>" alt="Event Banner" style="width: 100%; height: 200px; object-fit: cover;">
                            <div style="padding: 20px;">
                                <h3 style="color: var(--primary); margin-bottom: 10px;"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                                <p style="color: #aaa; margin-bottom: 5px;"><strong style="color: #fff;">Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($event['event_date'])); ?></p>
                                <p style="color: #aaa; margin-bottom: 5px;"><strong style="color: #fff;">Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                                <p style="color: #aaa; margin-bottom: 15px;"><strong style="color: #fff;">Duration:</strong> <?php echo $event['duration']; ?> Hours</p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #111; border-radius: 8px; border: 1px dashed #333;">
                        <p style="color: #888; font-size: 1.1rem;">No upcoming events scheduled right now. Stay tuned!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="movies" class="section">
        <div class="container">
            <h2 class="section-title">Featured Movies</h2>
            <div class="movie-grid">
                
                <?php if ($movies_result->num_rows > 0): ?>
                    <?php while ($row = $movies_result->fetch_assoc()): ?>
                        
                        <div class="movie-card" onclick="window.location.href='movie.php?id=<?php echo $row['id']; ?>'">
                            <img src="uploads/<?php echo htmlspecialchars($row['poster_image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?> Poster">
                            <div class="movie-info">
                                <h4><?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?></h4>
                                <span class="genre"><?php echo htmlspecialchars($row['genre'], ENT_QUOTES); ?></span>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted); text-align: center; width: 100%; padding: 20px;">No featured movies added yet. Admins are updating the library!</p>
                <?php endif; ?>

            </div>
            
        </div>
    </section>

    <section id="originals" class="section bg-darker">
        <div class="container">
            <h2 class="section-title">KUET Originals</h2>
            <div class="movie-grid">
                
                <div class="movie-card" onclick="window.location.href='original.php?film=shajghor'">
                    <img src="images/shajghor.jpg" alt="Shajghor">
                    <div class="movie-info" style="text-align: center;">
                        <h4>Shajghor</h4>
                        <span class="genre">Short Film</span><br>
                        <span style="font-size: 0.8rem; color: var(--primary); font-weight: bold; letter-spacing: 1px; display: inline-block; margin-top: 8px;">VIEW DETAILS</span>
                    </div>
                </div>
                
                <div class="movie-card" onclick="window.location.href='original.php?film=debi'">
                    <img src="images/debi.jpg" alt="Debi Music Video">
                    <div class="movie-info" style="text-align: center;">
                        <h4>Debi</h4>
                        <span class="genre">Music Video</span><br>
                        <span style="font-size: 0.8rem; color: var(--primary); font-weight: bold; letter-spacing: 1px; display: inline-block; margin-top: 8px;">VIEW DETAILS</span>
                    </div>
                </div>

                <div class="movie-card" onclick="window.location.href='original.php?film=satyajit'">
                    <img src="images/satyajit.jpg" alt="Tribute to Satyajit Ray">
                    <div class="movie-info" style="text-align: center;">
                        <h4>Tribute to Satyajit Ray</h4>
                        <span class="genre">Documentary / Tribute</span><br>
                        <span style="font-size: 0.8rem; color: var(--primary); font-weight: bold; letter-spacing: 1px; display: inline-block; margin-top: 8px;">VIEW DETAILS</span>
                    </div>
                </div>

                <div class="movie-card" onclick="window.location.href='original.php?film=perfect'">
                    <img src="images/perfect.jpg" alt="Perfect Cover">
                    <div class="movie-info" style="text-align: center;">
                        <h4>Perfect || Ed Sheeran Cover</h4>
                        <span class="genre">Music Video Cover</span><br>
                        <span style="font-size: 0.8rem; color: var(--primary); font-weight: bold; letter-spacing: 1px; display: inline-block; margin-top: 8px;">VIEW DETAILS</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="watchlist" class="section">
        <div class="container">
            <h2 class="section-title">My Watchlist</h2>
            <div id="watchlist-container" class="movie-grid">
                <p style="color: var(--text-muted);">Your watchlist is empty. Add some movies!</p>
            </div>
        </div>
    </section>

    <section id="profile" class="section bg-darker">
        <div class="container">
            <h2 class="section-title">Member Profile Preview</h2>
            
            <?php if (isset($_SESSION['user_id']) && $profile_data): ?>
                <?php 
                    // Check if they have a custom profile picture
                    $avatar = ($profile_data['profile_image'] != 'default.png' && !empty($profile_data['profile_image'])) 
                              ? 'uploads/' . htmlspecialchars($profile_data['profile_image']) 
                              : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png';
                ?>
                <div class="profile-container" style="max-width: 800px; margin: 0 auto;">
                    <div class="profile-header" style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px;">
                        <img src="<?php echo $avatar; ?>" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                        <div>
                            <h3 style="color: #fff; font-size: 1.6rem;"><?php echo htmlspecialchars($profile_data['full_name']); ?></h3>
                            <p style="color: #aaa; font-size: 0.95rem;">Joined: <?php echo date('F Y', strtotime($profile_data['created_at'])); ?> &nbsp;|&nbsp; Total Reviews: <span style="color: var(--primary); font-weight: bold;"><?php echo $review_count; ?></span></p>
                        </div>
                    </div>
                    
                    <div class="user-reviews" style="background: #111; padding: 25px; border-radius: 8px; border-left: 4px solid var(--primary);">
                        <h4 style="color: #fff; margin-bottom: 15px; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">Your Most Recent Review</h4>
                        
                        <?php if ($latest_review): ?>
                            <div class="review-item">
                                <h5 style="color: var(--primary); font-size: 1.2rem; margin-bottom: 8px;">
                                    <?php echo htmlspecialchars($latest_review['title']); ?> 
                                    <span style="color: #fff; font-size: 1rem; margin-left: 10px;">⭐ <?php echo $latest_review['rating']; ?>/10</span>
                                </h5>
                                <p style="color: #ccc; font-style: italic; line-height: 1.6;">"<?php echo nl2br(htmlspecialchars($latest_review['review_text'])); ?>"</p>
                            </div>
                        <?php else: ?>
                            <p style="color: #888;">You haven't reviewed any movies yet. Visit a movie page to drop your first rating!</p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div style="text-align: center; padding: 40px; background: #111; border-radius: 8px; border: 1px dashed #333; max-width: 800px; margin: 0 auto;">
                    <p style="color: #888; font-size: 1.1rem; margin-bottom: 20px;">Log in to view your personalized profile preview and recent cinematic reviews.</p>
                    <a href="login.php" class="btn-primary">Login Now</a>
                </div>
            <?php endif; ?>
            
        </div>
    </section>

    <footer>
        <p>&copy; 2026 KUET Film Club. All rights reserved.</p>
    </footer>

    <div id="movieModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Movie Details</h2>
            <p><strong>Title:</strong> Interstellar</p>
            <p><strong>Description:</strong> A team of explorers travel through a wormhole in space.</p>

            <div class="review-section">
                <h3>Leave a Review</h3>
                <textarea id="reviewText" placeholder="Write your thoughts..." rows="3"></textarea>
                <button class="btn-primary" onclick="submitReview()">Submit Review</button>
            </div>
        </div>
    </div>

    <div id="originalModal" class="modal">
        <div class="modal-content" style="max-width: 700px; background: rgba(20, 20, 24, 0.98); border: 1px solid var(--primary); text-align: left;">
            <span class="close" onclick="closeModal('originalModal')">&times;</span>
            
            <h2 id="originalTitle" style="margin-bottom: 20px; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; text-align: center;">Film Title</h2>
            
            <div style="text-align: center; margin-bottom: 25px;">
                <img id="originalImage" src="" alt="Cover" style="width: 100%; max-height: 350px; object-fit: cover; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.8);">
            </div>
            
            <div style="padding: 25px; background: #111; border-radius: 8px; border-left: 4px solid var(--primary);">
                <h4 style="color: #fff; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">Production Information</h4>
                <p id="originalDetails" style="color: #ccc; font-size: 1.05rem; line-height: 1.8;"></p>
            </div>
        </div>
    </div>

    <div id="galleryModal" class="modal">
        <div class="modal-content" style="max-width: 1000px; background: rgba(20, 20, 24, 0.95);">
            <span class="close" onclick="closeModal('galleryModal')">&times;</span>
            <h2 id="galleryTitle" style="color: var(--primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">Event Gallery</h2>
            
            <div id="galleryGrid" class="gallery-grid"></div>
        </div>
    </div>

    <script src="script.js"></script>

    <div id="dedicatedGalleryOverlay" class="gallery-overlay">
        <div class="gallery-interface-container">
            <div class="gallery-interface-header">
                <h2 id="interfaceTitle">Activity Gallery</h2>
                <button class="close-interface-btn" onclick="closeDedicatedGallery()">&times; Close View</button>
            </div>
            
            <p id="interfaceDescription" class="interface-subtitle"></p>
            
            <div id="interfacePhotoGrid" class="interface-photo-grid">
                </div>
        </div>
    </div>

</body>
</html>