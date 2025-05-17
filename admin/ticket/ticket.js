

// Tab switching functionality
    function switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('text-rwanda-blue', 'border-rwanda-blue');
            button.classList.add('text-gray-500', 'border-transparent');
        });
        document.getElementById('tab-' + tabName).classList.remove('text-gray-500', 'border-transparent');
        document.getElementById('tab-' + tabName).classList.add('text-rwanda-blue', 'border-rwanda-blue');
        
        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById('content-' + tabName).classList.add('active');
        
        // Load data for the selected tab - using a more reliable approach
        if (typeof window.loadTickets === 'function') {
            window.loadTickets(tabName);
        } else {
            console.error('loadTickets function is not defined');
        }
    }
    
 
    
    // Close modal
    function closeModal() {
        document.getElementById('ticket-modal').classList.add('hidden');
    }
    
    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Load new tickets by default - using a more reliable approach
        if (typeof window.loadTickets === 'function') {
            window.loadTickets('new');
        } else {
            // Fallback - try to call switchTab which will attempt to load tickets
            switchTab('new');
        }
        
        // Close modal when clicking outside
        const modal = document.getElementById('ticket-modal');
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
