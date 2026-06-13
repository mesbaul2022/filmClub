<?php
session_start();
require_once 'config/db.php';

// Fetch the latest movies added by the Admin
$movies_query = "SELECT * FROM movies ORDER BY created_at DESC LIMIT 8";
$movies_result = $conn->query($movies_query);
// Fetch upcoming events
$events_query = "SELECT * FROM events ORDER BY event_date ASC LIMIT 3";
$events_result = $conn->query($events_query);

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
                <div class="movie-card" onclick="playOriginal('Shadows of the Hall')">
                    <img src="https://images.unsplash.com/photo-1601506521937-0121a7fc2a6b?q=80&w=500&auto=format&fit=crop" alt="Short Film">
                    <div class="movie-info" style="text-align: center;">
                        <h4>Shadows of the Hall</h4>
                        <span class="genre">A KUET Independent Short</span><br>
                        <span style="font-size: 0.8rem; color: #888;">▶ Play Film</span>
                    </div>
                </div>
                
                <div class="movie-card" onclick="playOriginal('Echoes')">
                    <img src="https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=500&auto=format&fit=crop" alt="Echoes Short Film">
                    <div class="movie-info" style="text-align: center;">
                        <h4>Echoes</h4>
                        <span class="genre">An MI Studios Production</span><br>
                        <span style="font-size: 0.8rem; color: #888;">▶ Play Film</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Watchlist Section -->
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
            <div class="profile-container">
                <div class="profile-header">
                    <div class="avatar">KUET</div>
                    <div>
                        <h3>Mesbaul Islam</h3>
                        <p>Joined: April 2026 | Reviews: 1</p>
                    </div>
                </div>
                <div class="user-reviews">
                    <h4>Your Recent Reviews</h4>
                    <div class="review-item">
                        <h5>Interstellar <span class="stars">★★★★★</span></h5>
                        <p>"A visual masterpiece. The score by Hans Zimmer is unmatched."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="shop" class="section">
        <div class="container">
            <h2 class="section-title">Club Fair Shop</h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Reserve exclusive club merchandise to pick up at our upcoming campus stall.</p>
            
            <div class="grid-4">
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=300&auto=format&fit=crop" alt="T-Shirt" style="width: 100%; border-radius: 4px; margin-bottom: 15px;">
                    <h4>Director's Chair T-Shirt</h4>
                    <p style="font-size: 0.85rem; color: #aaa; margin-top: 5px;">Premium Custom Apparel.</p>
                    <button class="btn-primary" style="width: 100%;" onclick="reserveMerch('Custom T-Shirt')">Pre-order</button>
                </div>
                
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1580130601254-05fa235abeab?q=80&w=300&auto=format&fit=crop" alt="Poster" style="width: 100%; border-radius: 4px; margin-bottom: 15px;">
                    <h4>Classic Movie Posters</h4>
                    <p style="font-size: 0.85rem; color: #aaa; margin-top: 5px;">High-gloss A3 prints.</p>
                    <button class="btn-primary" style="width: 100%;" onclick="reserveMerch('Movie Poster')">Reserve</button>
                </div>
            </div>
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

    <div id="videoModal" class="modal">
        <div class="modal-content" style="max-width: 800px; text-align: center;">
            <span class="close" onclick="closeModal('videoModal')">&times;</span>
            <h2 id="videoTitle" style="margin-bottom: 20px; color: var(--primary);">Playing Video...</h2>
            <div style="background: #000; height: 400px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #333;">
                <p style="color: #666; letter-spacing: 2px;">[ VIDEO PLAYER ENGINE ]</p>
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