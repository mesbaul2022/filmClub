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
    
    if (event.target == movieModal) closeModal('movieModal');
    if (event.target == videoModal) closeModal('videoModal');
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