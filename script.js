// Function to open the modal
// We will trigger this by adding onclick="openModal()" to our movie cards later
function openModal() {
    document.getElementById('movieModal').style.display = "block";
}

// Function to close the modal
function closeModal() {
    document.getElementById('movieModal').style.display = "none";
}

// Close modal if user clicks outside of the box
window.onclick = function(event) {
    let modal = document.getElementById('movieModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
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