<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "db_setup.php";
?>

<!-- Ticket Tracking Modal -->
<div id="check-ticket-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex flex-col items-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4" data-translate="track_ticket">Track Your Ticket</h3>
            
            <!-- Search Form -->
            <div class="w-full mb-6">
                <form id="ticket-search-form" class="flex flex-col space-y-4">
                    <div class="flex flex-col">
                        <label for="ticket-identifier" class="text-sm text-gray-600 mb-1" data-translate="enter_ticket_info">Enter your ticket number or phone number</label>
                        <input type="text" id="ticket-identifier" name="ticket-identifier" placeholder="TKT-00-0000 or 07xxxxxxxxxx" 
                            class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-rwandan-blue">
                    </div>
                    <button type="submit" class="bg-rwandan-blue text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300" data-translate="search_ticket">
                        Search Ticket
                    </button>
                </form>
            </div>
            
            <!-- Results Area (initially hidden) -->
            <div id="ticket-results" class="w-full hidden">
                <!-- Ticket information will be loaded here -->
            </div>
            
            <!-- Loading Indicator -->
            <div id="ticket-loading" class="hidden">
                <svg class="animate-spin h-8 w-8 text-rwandan-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-gray-600" data-translate="searching">Searching...</p>
            </div>
            
            <!-- Error Message -->
            <div id="ticket-error" class="hidden w-full p-4 bg-red-100 text-red-700 rounded-lg">
                <p data-translate="no_ticket_found">No ticket found with the provided information.</p>
            </div>
            
            <!-- Close Button -->
            <div class="text-right w-full mt-4">
                <button id="close-check-ticket-modal" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for the modal functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const checkTicketModal = document.getElementById('check-ticket-modal');
    const closeCheckTicketModal = document.getElementById('close-check-ticket-modal');
    const ticketSearchForm = document.getElementById('ticket-search-form');
    const ticketResults = document.getElementById('ticket-results');
    const ticketLoading = document.getElementById('ticket-loading');
    const ticketError = document.getElementById('ticket-error');
    
    // Open modal when track status button is clicked
    const trackStatusButtons = document.querySelectorAll('.bg-rwandan-yellow');
    trackStatusButtons.forEach(button => {
        button.addEventListener('click', function() {
            checkTicketModal.classList.remove('hidden');
        });
    });
    
    // Close modal when close button is clicked
    closeCheckTicketModal.addEventListener('click', function() {
        checkTicketModal.classList.add('hidden');
        // Reset form and results
        ticketSearchForm.reset();
        ticketResults.classList.add('hidden');
        ticketError.classList.add('hidden');
    });
    
    // Handle form submission
    ticketSearchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get the input value
        const identifier = document.getElementById('ticket-identifier').value.trim();
        
        if (!identifier) {
            alert('Please enter a ticket number or phone number');
            return;
        }
        
        // Show loading indicator
        ticketLoading.classList.remove('hidden');
        ticketResults.classList.add('hidden');
        ticketError.classList.add('hidden');
        
        // Send AJAX request to fetch ticket data
        fetch('fetch_ticket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'identifier=' + encodeURIComponent(identifier)
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading indicator
            ticketLoading.classList.add('hidden');
            
            if (data.error) {
                // Show error message
                ticketError.classList.remove('hidden');
                ticketError.querySelector('p').textContent = data.error;
            } else {
                // Show results
                ticketResults.classList.remove('hidden');
                ticketResults.innerHTML = data.html;
                
                // Initialize any event listeners for the response interface
                initializeResponseInterface();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ticketLoading.classList.add('hidden');
            ticketError.classList.remove('hidden');
            ticketError.querySelector('p').textContent = 'An error occurred while fetching the ticket information.';
        });
    });
    
    // Function to initialize event listeners for the response interface
    function initializeResponseInterface() {
        // Handle satisfaction buttons
        const satisfiedBtn = document.getElementById('satisfied-btn');
        const notSatisfiedBtn = document.getElementById('not-satisfied-btn');
        
        if (satisfiedBtn) {
            satisfiedBtn.addEventListener('click', function() {
                const ticketId = document.getElementById('send-message-form').getAttribute('data-ticket-id');
                
                // Show loading
                document.getElementById('satisfaction-options').classList.add('hidden');
                
                // Send AJAX request to mark ticket as completed
                fetch('mark_ticket_completed.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'ticket_id=' + encodeURIComponent(ticketId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('satisfied-message').classList.remove('hidden');
                    } else {
                        alert('Failed to update ticket status: ' + data.error);
                        document.getElementById('satisfaction-options').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the ticket status.');
                    document.getElementById('satisfaction-options').classList.remove('hidden');
                });
            });
        }
        
        if (notSatisfiedBtn) {
            notSatisfiedBtn.addEventListener('click', function() {
                document.getElementById('satisfaction-options').classList.add('hidden');
                document.getElementById('reopen-chat').classList.remove('hidden');
            });
        }
        
        // Handle sending new message
        const sendMessageForm = document.getElementById('send-message-form');
        if (sendMessageForm) {
            sendMessageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const ticketId = this.getAttribute('data-ticket-id');
                const message = document.getElementById('new-message').value.trim();
                
                if (!message) {
                    alert('Please enter a message');
                    return;
                }
                
                // Show loading
                document.getElementById('message-loading').classList.remove('hidden');
                
                // Send AJAX request to save the message
                fetch('save_response.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'ticket_id=' + encodeURIComponent(ticketId) + '&message=' + encodeURIComponent(message)
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('message-loading').classList.add('hidden');
                    
                    if (data.success) {
                        // Clear the input
                        document.getElementById('new-message').value = '';
                        
                        // Add the new message to the chat
                        const chatMessages = document.getElementById('chat-messages');
                        const newMessage = document.createElement('div');
                        newMessage.className = 'bg-blue-100 p-3 rounded-lg mb-2 self-end';
                        newMessage.innerHTML = `
                            <p class="text-sm">${message}</p>
                            <p class="text-xs text-gray-500 text-right">Just now</p>
                        `;
                        chatMessages.appendChild(newMessage);
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                        
                        // Show confirmation
                        document.getElementById('message-sent').classList.remove('hidden');
                        setTimeout(() => {
                            document.getElementById('message-sent').classList.add('hidden');
                        }, 3000);
                    } else {
                        alert('Failed to send message: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('message-loading').classList.add('hidden');
                    alert('An error occurred while sending your message.');
                });
            });
        }
    }
});
</script>