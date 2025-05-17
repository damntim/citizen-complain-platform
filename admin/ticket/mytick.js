let currentPage = 1;

function fetchTickets(direction = '') {
    if (direction === 'next') currentPage++;
    if (direction === 'prev' && currentPage > 1) currentPage--;

    const search = document.getElementById("search-ticket").value;
    const sort = document.getElementById("sort-ticket").value;

    fetch(`fetch_ongoing_tickets.php?search=${encodeURIComponent(search)}&sort=${sort}&page=${currentPage}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById("ongoing-tickets-table").innerHTML = data;
            // Optionally update page indicators here
        })
        .catch(error => {
            console.error("Error fetching tickets:", error);
        });
}

// Event listeners
document.getElementById("search-ticket").addEventListener("input", () => {
    currentPage = 1;
    fetchTickets();
});

document.getElementById("sort-ticket").addEventListener("change", () => {
    currentPage = 1;
    fetchTickets();
});

document.getElementById("ongoing-prev").addEventListener("click", () => fetchTickets('prev'));
document.getElementById("ongoing-next").addEventListener("click", () => fetchTickets('next'));

// Initial load
document.addEventListener("DOMContentLoaded", fetchTickets);
