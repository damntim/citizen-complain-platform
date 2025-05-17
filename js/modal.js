document.addEventListener('DOMContentLoaded', function() {
    // Get the modal
    const ticketModal = document.getElementById('ticket-modal');
    
    // Get all buttons that should open the modal
    const openModalButtons = document.querySelectorAll('.open-ticket-modal');
    
    // Get the close button
    const closeModalButton = ticketModal.querySelector('.close-modal');
    
    // Function to open the modal
    function openModal() {
        ticketModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    
    // Function to close the modal
    function closeModal() {
        ticketModal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Add click event to all open modal buttons
    openModalButtons.forEach(button => {
        button.addEventListener('click', openModal);
    });
    
    // Add click event to close button
    closeModalButton.addEventListener('click', closeModal);
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === ticketModal) {
            closeModal();
        }
    });
});