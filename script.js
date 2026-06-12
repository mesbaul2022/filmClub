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
// Search and Filter Logic
document.getElementById('searchInput').addEventListener('keyup', function() {
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

// 1. Local Image Mapping Database
// Modifying instructions: Swap or append new image file paths directly inside these arrays
const customInterfacesData = {
    screenings: {
        title: "Cinematic Screenings",
        description: "Archived snapshots of major screening productions, hall exhibitions, and community viewings inside the KUET Auditorium.",
        images: [
            "./images/movie 4.jpg",
            "./images/movie show.jpg",
            "./images/daruchini moovie show.jpg",
            "./images/Doob Movie show.jpg",
            "./images/Utshob movie show.jpg"
        ]
    },
    podcasts: {
        title: "Podcasts & Discussions",
        description: "Capturing deep analytical sessions, film review recordings, panel forums, and our signature 'Humayun Adda' sessions.",
        images: [
            "./images/podcast 1.jpg",
            "./images/podcast.jpg",
            "./images/INTERACTIVE SESSION.jpg"
        ]
    },
    originals: {
        title: "Original Content & Contests",
        description: "Spotlighting homegrown cinematic achievements, independent short film productions, and official festival entries from inside KUET.",
        images: [
            "./images/Contest.jpg",
            "./images/Kuert original.jpg",
            "./images/Kuert original 2.jpg",
            "./images/Kuert original 3.jpg",
            "./images/Program 2.jpg"
        ]
    },
    fairs: {
        title: "Festivals & Recognitions",
        description: "Commemorating external delegation tours, campus stall setups during the Club Fair, and official recognitions like the 'Operation Sundarban' project reception.",
        images: [
            "./images/club fair.jpg",
            "./images/club fair 2.jpg",
            "./images/club fair 3.jpg",
            "./images/club fair 4.jpg",
            "./images/sunderban recognition.jpg",
            "./images/sunderban recognition 2.jpg"
        ]
    }
};

// 2. Action Controller to Generate and Open the Interface
function openDedicatedGallery(categoryKey) {
    // Locate the structural targets inside the document
    const overlay = document.getElementById('dedicatedGalleryOverlay');
    const titleContainer = document.getElementById('interfaceTitle');
    const descContainer = document.getElementById('interfaceDescription');
    const gridContainer = document.getElementById('interfacePhotoGrid');
    
    // Extract data matching the selected category key
    const targetData = customInterfacesData[categoryKey];
    
    // Inject the contextual headings and text labels
    titleContainer.innerText = targetData.title;
    descContainer.innerText = targetData.description;
    
    // Flush out previous memory contents of the photo grid
    gridContainer.innerHTML = '';
    
    // Loop through the mapped paths database to reconstruct structural picture frames
    targetData.images.forEach(photoPath => {
        // Create an outer card frame container
        const cardFrame = document.createElement('div');
        cardFrame.className = 'interface-photo-item';
        
        // Create the individual raw image tag element
        const imageTag = document.createElement('img');
        imageTag.src = photoPath;
        imageTag.alt = "KUET Film Club Historical Record Image";
        
        // Append elements structural hierarchy tree
        cardFrame.appendChild(imageTag);
        gridContainer.appendChild(cardFrame);
    });
    
    // Bring the full viewport container display properties live
    overlay.style.display = "block";
    document.body.style.overflow = "hidden"; // Locks main background scrolling
}

// 3. Close Functionality to Return back to home layout
function closeDedicatedGallery() {
    document.getElementById('dedicatedGalleryOverlay').style.display = "none";
    document.body.style.overflow = "auto"; // Re-enables document body scroll
}