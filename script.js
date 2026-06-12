// Function to open specific modals
function openModal(modalId = 'movieModal', movieTitle = '') {
    document.getElementById(modalId).style.display = "block";
    document.body.style.overflow = "hidden";
    
    if(modalId === 'movieModal') {
        window.currentMovieSelection = movieTitle; 
    }
}

// Function to close specific modals
function closeModal(modalId = 'movieModal') {
    document.getElementById(modalId).style.display = "none";
    document.body.style.overflow = "auto";
}

// Close modal if user clicks outside of the box
window.onclick = function(event) {
    let movieModal = document.getElementById('movieModal');
    let videoModal = document.getElementById('videoModal');
    let galleryModal = document.getElementById('galleryModal'); // We added this
    
    if (event.target == movieModal) closeModal('movieModal');
    if (event.target == videoModal) closeModal('videoModal');
    if (event.target == galleryModal) closeModal('galleryModal'); // We added this
}

// KUET Originals Player Logic
function playOriginal(title) {
    document.getElementById('videoTitle').innerText = "Now Playing: " + title;
    openModal('videoModal');
}

console.log("JavaScript Loaded! Ready to manage KUET Film Club interactions.");

// Search and Filter Logic (Made Safe)
const searchInputBox = document.getElementById('searchInput');

if (searchInputBox) { // This 'if' statement stops the crash!
    searchInputBox.addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.movie-card');
        
        cards.forEach(card => {
            let title = card.querySelector('h4').innerText.toLowerCase();
            if (title.includes(filter)) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
}

function filterMovies(category) {
    let cards = document.querySelectorAll('.movie-card');
    cards.forEach(card => {
        let genre = card.querySelector('.genre').innerText;
        if (category === 'All' || genre.includes(category)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

// Add to Watchlist Logic
let watchlist = [];

// Modify the openModal function to pass the movie title
function openModal(modalId, movieTitle = '') {
    document.getElementById(modalId).style.display = "block";
    document.body.style.overflow = "hidden";
    
    // If opening a movie modal, save the current title to a global variable
    if(modalId === 'movieModal') {
        window.currentMovieSelection = movieTitle; 
    }
}

// Add a function to handle adding to watchlist
function addToWatchlist() {
    let movie = window.currentMovieSelection;
    if (!watchlist.includes(movie) && movie) {
        watchlist.push(movie);
        alert(movie + " added to your Watchlist!");
        renderWatchlist();
    } else {
        alert("This movie is already in your watchlist.");
    }
}

// Render the watchlist UI
function renderWatchlist() {
    let container = document.getElementById('watchlist-container');
    container.innerHTML = ''; // Clear current
    
    watchlist.forEach(movie => {
        let div = document.createElement('div');
        div.className = 'movie-card';
        div.innerHTML = `<div class="movie-info"><h4>${movie}</h4><p class="genre">Saved for later</p></div>`;
        container.appendChild(div);
    });
}

// Submit Review Logic
function submitReview() {
    let text = document.getElementById('reviewText').value;
    let movie = window.currentMovieSelection || "A Movie";
    
    if (text.trim() === "") {
        alert("Please write a review first.");
        return;
    }

    // Find the user profile reviews container
    let reviewContainer = document.querySelector('.user-reviews');
    
    // Create new review element
    let newReview = document.createElement('div');
    newReview.className = 'review-item';
    newReview.innerHTML = `
        <h5>${movie} <span class="stars">★★★★★</span></h5>
        <p>"${text}"</p>
    `;
    
    // Add it to the profile
    reviewContainer.appendChild(newReview);
    
    // Clear the text area and close modal
    document.getElementById('reviewText').value = "";
    closeModal('movieModal');
    alert("Review posted successfully!");
}

// Merchandise Reservation Logic
function reserveMerch(itemName) {
    let confirmReserve = confirm(`Would you like to reserve one ${itemName} for pickup at the KUET Club Fair stall?`);
    
    if (confirmReserve) {
        alert(`Success! Your ${itemName} has been reserved. Please show your student ID at the stall.`);
    }
}

// --- NEW GALLERY LOGIC ---
const galleryData = {
    screenings: {
        title: "Cinematic Screenings",
        images: [
            "./images/movie 4.jpg",
            "./images/Haoa.jpg",
            "./images/Doob Movie show.jpg",
            "./images/Utshob movie show.jpg"
        ]
    },
    podcasts: {
        title: "Podcasts & Discussions",
        images: [
            "./images/podcast 1.jpg",
            "./images/podcast.jpg",
            "./images/INTERACTIVE SESSION.jpg"
        ]
    },
    originals: {
        title: "KUET Originals & Contests",
        images: [
            "./images/Contest.jpg",
            "./images/Kuert original.jpg",
            "./images/Kuert original 2.jpg",
            "./images/Kuert original 3.jpg"
        ]
    },
    fairs: {
        title: "Festivals & Recognitions",
        images: [
            "./images/club fair.jpg",
            "./images/club fair 2.jpg",
            "./images/club fair 3.jpg",
            "./images/sunderban recognition.jpg"
        ]
    }
};

function openGallery(categoryKey) {
    const data = galleryData[categoryKey];
    document.getElementById('galleryTitle').innerText = data.title;
    
    const grid = document.getElementById('galleryGrid');
    grid.innerHTML = ''; 
    
    data.images.forEach(imagePath => {
        let img = document.createElement('img');
        img.src = imagePath;
        img.alt = "KUET Film Club Event";
        grid.appendChild(img);
    });
    
    openModal('galleryModal');
}



//      DEDICATED MULTI-INTERFACE GALLERY ENGINE

// 1. Local Image Mapping Database (Updated with full library and descriptions)
const customInterfacesData = {
    screenings: {
        title: "Cinematic Screenings",
        description: "Archived snapshots of major screening productions, hall exhibitions, and community viewings inside the KUET Auditorium.",
        images: [
            { src: "./images/Daruchini Dip poster.jpg", desc: "Daruchini Dip Official Poster" },
            { src: "./images/Daruchini Dip movie show at Auditorium.jpg", desc: "Daruchini Dip Screening" },
            { src: "./images/Movie show at Auditorium.jpg", desc: "Auditorium Crowd View" },
            { src: "./images/Movie show at Auditorium (image 2).jpg", desc: "Auditorium Wide Angle" },
            { src: "./images/Movie show at Auditorium (image 3).jpg", desc: "Auditorium Back View" },
            { src: "./images/Doob Poster.jpg", desc: "Doob Official Poster" },
            { src: "./images/Doob Movie show.jpg", desc: "Doob Movie Show Live" },
            { src: "./images/Haoa Poster.jpg", desc: "Haoa Official Poster" },
            { src: "./images/Haoa Poster (image 2).jpg", desc: "Haoa Poster Variant" },
            { src: "./images/Haoa movie primier at Auditorium.jpg", desc: "Haoa Premiere at KUET" },
            { src: "./images/Haoa movie primier at Auditorium (image 2).jpg", desc: "Haoa Premiere Crowd" },
            { src: "./images/Utshob Poster.jpg", desc: "Utshob Official Poster" },
            { src: "./images/Utshob movie show at Auditorium.jpg", desc: "Utshob Movie Screening" },
            { src: "./images/Utshob movie primier at Auditorium.jpg", desc: "Utshob Premiere Event" }
        ]
    },
    podcasts: {
        title: "Podcasts & Discussions",
        description: "Capturing deep analytical sessions, film review recordings, panel forums, and our signature 'Humayun Adda' sessions.",
        images: [
            { src: "./images/Podcast - Humayun Adda.jpg", desc: "Humayun Adda Podcast Session" },
            { src: "./images/Podcast - Humayun Adda (image 2).jpg", desc: "Humayun Adda Discussion" },
            { src: "./images/Interactive Session.jpg", desc: "Interactive Filmmaking Session" }
        ]
    },
    originals: {
        title: "Original Content & Contests",
        description: "Spotlighting homegrown cinematic achievements, independent short film productions, and official festival entries from inside KUET.",
        images: [
            { src: "./images/Short Film Contest.jpg", desc: "Intra-KUET Short Film Contest" },
            { src: "./images/Kuert original premiere show (image 1).jpg", desc: "KUET Original Premiere" },
            { src: "./images/Kuert original premiere show (image 2).jpg", desc: "Student Director Showcase" },
            { src: "./images/Kuert original premiere show (image 3).jpg", desc: "Original Film Screening" }
        ]
    },
    fairs: {
        title: "Festivals & Recognitions",
        description: "Commemorating external delegation tours, campus stall setups during the Club Fair, and official recognitions like the 'Operation Sundarban' project reception.",
        images: [
            { src: "./images/club fair (image 1).jpg", desc: "Club Fair Stall Setup" },
            { src: "./images/club fair (image 2).jpg", desc: "Club Fair Member Registration" },
            { src: "./images/club fair (image 3).jpg", desc: "Club Fair Student Engagement" },
            { src: "./images/club fair (image 4).jpg", desc: "Club Fair Organizing Team" },
            { src: "./images/Operation Sundarban movie recognition event.jpg", desc: "Operation Sundarban Recognition" },
            { src: "./images/Operation Sundarban movie recognition event (image 2).jpg", desc: "Operation Sundarban Cast & Crew" },
            { src: "./images/Cultural Program (image 1).jpg", desc: "Campus Cultural Program" },
            { src: "./images/Club Program (image 1).jpg", desc: "Film Club Evening Program" },
            { src: "./images/Club Program (image 2).jpg", desc: "Club Gathering" },
            { src: "./images/Club Program (image 3).jpg", desc: "Club Program Activity" }
        ]
    }
};

// 2. Action Controller to Generate and Open the Interface
function openDedicatedGallery(categoryKey) {
    const overlay = document.getElementById('dedicatedGalleryOverlay');
    const titleContainer = document.getElementById('interfaceTitle');
    const descContainer = document.getElementById('interfaceDescription');
    const gridContainer = document.getElementById('interfacePhotoGrid');
    
    const targetData = customInterfacesData[categoryKey];
    
    titleContainer.innerText = targetData.title;
    descContainer.innerText = targetData.description;
    gridContainer.innerHTML = ''; // Clears the grid
    
    // Loop through the database to reconstruct picture frames
    targetData.images.forEach(photoData => {
        // 1. Create the outer card
        const cardFrame = document.createElement('div');
        cardFrame.className = 'interface-photo-item';
        
        // 2. Create the image
        const imageTag = document.createElement('img');
        imageTag.src = photoData.src; 
        imageTag.alt = photoData.desc;
        
        // 3. CREATE THE DESCRIPTION TEXT (This is what was missing!)
        const textTag = document.createElement('p');
        textTag.className = 'photo-description';
        textTag.innerText = photoData.desc; 
        
        // 4. Attach both the image AND the text to the card
        cardFrame.appendChild(imageTag);
        cardFrame.appendChild(textTag); 
        
        // 5. Put the card in the grid
        gridContainer.appendChild(cardFrame);
    });
    
    overlay.style.display = "block";
    document.body.style.overflow = "hidden"; 
}

// 3. Close Functionality to Return back to home layout
function closeDedicatedGallery() {
    document.getElementById('dedicatedGalleryOverlay').style.display = "none";
    document.body.style.overflow = "auto"; // Re-enables document body scroll
}