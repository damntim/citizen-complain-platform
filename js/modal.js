document.addEventListener('DOMContentLoaded', function() {
    // Get the modals
    const ticketModal = document.getElementById('ticket-modal');
    const checkTicketModal = document.getElementById('check-ticket-modal');
    
    // Get all buttons that should open the ticket modal
    const openModalButtons = document.querySelectorAll('.open-ticket-modal');
    
    // Get all buttons that should open the check ticket modal
    const openCheckModalButtons = document.querySelectorAll('.open-check-ticket-modal');
    
    
    // Get the close buttons
    const closeModalButton = ticketModal.querySelector('.close-modal');
    const closeCheckModalButton = checkTicketModal.querySelector('.close-modal');
    
    // Function to open the ticket modal
    function openModal() {
        ticketModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    
    // Function to open the check ticket modal
    function openCheckModal() {
        checkTicketModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    
    // Function to close the ticket modal
    function closeModal() {
        ticketModal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Function to close the check ticket modal
    function closeCheckModal() {
        checkTicketModal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Add click event to all open ticket modal buttons
    openModalButtons.forEach(button => {
        button.addEventListener('click', openModal);
    });
    
    // Add click event to all open check ticket modal buttons
    openCheckModalButtons.forEach(button => {
        button.addEventListener('click', openCheckModal);
    });
    
    // Add click event to close buttons
    closeModalButton.addEventListener('click', closeModal);
    closeCheckModalButton.addEventListener('click', closeCheckModal);
    
    // Close modals when clicking outside of them
    window.addEventListener('click', function(event) {
        if (event.target === ticketModal) {
            closeModal();
        }
        if (event.target === checkTicketModal) {
            closeCheckModal();
        }
    });
});