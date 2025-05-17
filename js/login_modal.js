document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const loginModal = document.getElementById('login-modal');
    const loginForm = document.getElementById('login-form');
    const forgotPasswordForm = document.getElementById('forgot-password-form');
    const registerForm = document.getElementById('register-form');
    
    // Open modal buttons
    const openLoginButtons = document.querySelectorAll('.open-login-modal');
    
    // Close modal button
    const closeLoginModal = document.querySelector('.close-login-modal');
    
    // Form navigation links
    const forgotPasswordLink = document.getElementById('forgot-password-link');
    const backToLoginLink = document.getElementById('back-to-login');
    const showRegisterFormLink = document.getElementById('show-register-form');
    const backToLoginFromRegisterLink = document.getElementById('back-to-login-from-register');
    
    // Modal title
    const modalTitle = document.getElementById('modal-title');
    
    // Check if admin exists
    checkAdminExists();
    
    // Open modal function
    function openModal() {
        loginModal.classList.remove('hidden');
        // Reset to login form
        showLoginForm();
    }
    
    // Close modal function
    function closeModal() {
        loginModal.classList.add('hidden');
    }
    
    // Show login form
    function showLoginForm() {
        loginForm.classList.remove('hidden');
        forgotPasswordForm.classList.add('hidden');
        registerForm.classList.add('hidden');
        modalTitle.textContent = document.querySelector('[data-translate="login"]').textContent;
    }
    
    // Show forgot password form
    function showForgotPasswordForm() {
        loginForm.classList.add('hidden');
        forgotPasswordForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        modalTitle.textContent = document.querySelector('[data-translate="forgot_password"]').textContent;
    }
    
    // Show register form
    function showRegisterForm() {
        loginForm.classList.add('hidden');
        forgotPasswordForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        modalTitle.textContent = document.querySelector('[data-translate="create_account"]').textContent;
    }
    
    // Check if admin exists in the database
    function checkAdminExists() {
        fetch('check_admin.php')
            .then(response => response.json())
            .then(data => {
                const adminCheckMessage = document.getElementById('admin-check-message');
                if (data.adminExists === false) {
                    adminCheckMessage.classList.remove('hidden');
                } else {
                    adminCheckMessage.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error checking admin:', error);
            });
    }
    
    // Event listeners
    openLoginButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    });
    
    closeLoginModal.addEventListener('click', closeModal);
    
    // Close modal when clicking outside
    loginModal.addEventListener('click', function(e) {
        if (e.target === loginModal) {
            closeModal();
        }
    });
    
    // Form navigation
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            showForgotPasswordForm();
        });
    }
    
    if (backToLoginLink) {
        backToLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });
    }
    
    if (showRegisterFormLink) {
        showRegisterFormLink.addEventListener('click', function(e) {
            e.preventDefault();
            showRegisterForm();
        });
    }
    
    if (backToLoginFromRegisterLink) {
        backToLoginFromRegisterLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });
    }
    
    // Form validation
    const loginFormElement = document.getElementById('login-form-element');
    if (loginFormElement) {
        loginFormElement.addEventListener('submit', function(e) {
            const identifier = document.getElementById('login-identifier').value;
            const password = document.getElementById('login-password').value;
            
            if (!identifier || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    }
});