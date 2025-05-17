<!-- Login and Registration Modal -->
<div id="login-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-rwandan-green text-white px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold" id="modal-title" data-translate="login">Injira</h3>
            <button class="close-login-modal text-white hover:text-gray-200 transition duration-150">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Success and Error Messages -->
<?php if (!empty($successMessage)): ?>
<div id="success-message" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 shadow-md">
    <div class="flex items-center">
        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
    <button class="absolute top-0 right-0 mt-2 mr-2 text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
<?php endif; ?>

<?php if (!empty($errorMessage)): ?>
<div id="error-message" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-md">
    <div class="flex items-center">
        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <span><?php echo htmlspecialchars($errorMessage); ?></span>
    </div>
    <button class="absolute top-0 right-0 mt-2 mr-2 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
<?php endif; ?>

        <!-- Modal Content -->
        <div class="px-6 py-4">
            <!-- Login Form -->
            <div id="login-form">
                <form id="login-form-element" action="handle_login.php" method="post">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-identifier" data-translate="email_or_phone">
                            Email cyangwa Telefone
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="login-identifier" name="login-identifier" type="text" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-password" data-translate="password">
                            Ijambo ry'ibanga
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="login-password" name="login-password" type="password" required>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <button class="bg-rwandan-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" 
                            type="submit" data-translate="login_button">
                            Injira
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-rwandan-blue hover:text-blue-800" href="#" id="forgot-password-link" data-translate="forgot_password">
                            Wibagiwe ijambo ry'ibanga?
                        </a>
                    </div>
                </form>
                <div id="admin-check-message">
                    <div class="flex flex-col items-center justify-center mt-4 border-t pt-4 border-gray-200">
                        <p class="text-center text-sm text-gray-600 mb-3" data-translate="no_admin_exists">
                            Nta muyobozi uri mu bubiko bw'amakuru
                        </p>
                        <button id="show-register-form" class="bg-rwandan-green hover:bg-green-700 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline transition duration-300 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span data-translate="create_admin_account">Kora konti y'umuyobozi</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Forgot Password Form -->
            <div id="forgot-password-form" class="hidden">
                <form action="handle_forgot_password.php" method="post">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="forgot-email" data-translate="email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="forgot-email" name="forgot-email" type="email" required>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <button class="bg-rwandan-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" 
                            type="submit" data-translate="reset_password">
                            Hindura ijambo ry'ibanga
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-rwandan-blue hover:text-blue-800" href="#" id="back-to-login" data-translate="back_to_login">
                            Subira ku ifishi yo kwinjira
                        </a>
                    </div>
                </form>
            </div>

            <!-- Registration Form -->
            <div id="register-form" class="hidden">
                <form action="handle_registration.php" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-fullname" data-translate="full_name">
                            Amazina yose
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="register-fullname" name="register-fullname" type="text" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-email" data-translate="email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="register-email" name="register-email" type="email" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-phone" data-translate="phone">
                            Telefone
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="register-phone" name="register-phone" type="tel" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="profile-image" data-translate="profile_image">
                            Ifoto y'umwirondoro
                        </label>
                        <input class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-rwandan-blue file:text-white hover:file:bg-blue-700 transition duration-300" 
                            id="profile-image" name="profile-image" type="file" accept="image/*">
                        <p class="text-xs text-gray-500 mt-1" data-translate="image_hint">Hitamo ifoto y'umwirondoro (JPG, PNG)</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-password" data-translate="password">
                            Ijambo ry'ibanga
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="register-password" name="register-password" type="password" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-confirm-password" data-translate="confirm_password">
                            Emeza ijambo ry'ibanga
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="register-confirm-password" name="register-confirm-password" type="password" required>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <button class="bg-rwandan-green hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300" 
                            type="submit" data-translate="create_account">
                            Kora konti
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-rwandan-blue hover:text-blue-800" href="#" id="back-to-login-from-register" data-translate="back_to_login">
                            Subira ku ifishi yo kwinjira
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-100 px-6 py-3">
            <p class="text-xs text-gray-600 text-center" data-translate="secure_login">
                Iyi serivisi ikoreshwa n'abayobozi gusa. Amakuru yose arindwa.
            </p>
        </div>
    </div>
</div>