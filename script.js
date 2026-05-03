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

// Optional: Test the modal automatically opens for testing purposes
console.log("JavaScript Loaded! Ready to manage KUET Film Club interactions.");