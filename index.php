
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$successMessage = '';
$errorMessage = '';

if (isset($_SESSION['register_success'])) {
    $successMessage = $_SESSION['register_success'];
    unset($_SESSION['register_success']); 
}

if (isset($_SESSION['register_error'])) {
    $errorMessage = $_SESSION['register_error'];
    unset($_SESSION['register_error']); 
}
?>

<!DOCTYPE html>
<html lang="rw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Engagement System - Rwanda</title>
    
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles based on Rwandan flag colors */
        :root {
            --rwandan-blue: #00a0d1;
            --rwandan-yellow: #fad201;
            --rwandan-green: #20603d;
        }

        .text-rwandan-blue {
            color: var(--rwandan-blue);
        }

        .text-rwandan-yellow {
            color: var(--rwandan-yellow);
        }

        .text-rwandan-green {
            color: var(--rwandan-green);
        }

        .bg-rwandan-blue {
            background-color: var(--rwandan-blue);
        }

        .bg-rwandan-yellow {
            background-color: var(--rwandan-yellow);
        }

        .bg-rwandan-green {
            background-color: var(--rwandan-green);
        }

        .border-rwandan-blue {
            border-color: var(--rwandan-blue);
        }

        .border-rwandan-yellow {
            border-color: var(--rwandan-yellow);
        }

        .border-rwandan-green {
            border-color: var(--rwandan-green);
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .sun-rays {
            position: absolute;
            width: 40px;
            height: 40px;
            top: -5px;
            right: -5px;
            animation: spin 60s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .header-gradient {
            background: linear-gradient(135deg, var(--rwandan-blue) 0%, var(--rwandan-green) 100%);
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    
    <header class="relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-full z-0 opacity-20">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none" class="w-full h-full">
                <path fill="#20603d" fill-opacity="0.5" d="M0,160L48,138.7C96,117,192,75,288,69.3C384,64,480,96,576,128C672,160,768,192,864,186.7C960,181,1056,139,1152,138.7C1248,139,1344,181,1392,202.7L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            </svg>
        </div>

        
        <nav class="relative z-10 bg-white shadow-md">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    
                    <div class="flex items-center space-x-2 md:space-x-3">
                        
                        <div class="relative w-8 h-8 md:w-12 md:h-12">
                            <svg viewBox="0 0 100 100" class="w-full h-full">
                                
                                <rect x="0" y="0" width="100" height="33.33" fill="#00a0d1" />
                                
                                <rect x="0" y="33.33" width="100" height="33.33" fill="#fad201" />
                                
                                <rect x="0" y="66.66" width="100" height="33.33" fill="#20603d" />
                                
                                <circle cx="70" cy="33.33" r="15" fill="#fad201" class="floating" />
                            </svg>
                            
                            <svg viewBox="0 0 100 100" class="sun-rays">
                                <path d="M50,0 L54,32 L50,28 L46,32 Z M50,100 L54,68 L50,72 L46,68 Z M0,50 L32,46 L28,50 L32,54 Z M100,50 L68,46 L72,50 L68,54 Z M14.64,14.64 L39.9,38.1 L34.14,38.1 L34.14,43.86 Z M85.36,85.36 L60.1,61.9 L65.86,61.9 L65.86,56.14 Z M14.64,85.36 L38.1,60.1 L38.1,65.86 L43.86,65.86 Z M85.36,14.64 L61.9,39.9 L61.9,34.14 L56.14,34.14 Z" fill="#fad201" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold">
                                <span class="text-rwandan-blue">Mu</span><span class="text-rwandan-yellow">ra</span><span class="text-rwandan-green">kaza</span>
                            </h1>
                            <p class="text-gray-600 text-xs md:text-sm">Citizen Engagement Portal</p>
                        </div>
                    </div>

                    
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="#home" class="text-sm text-gray-700 hover:text-rwandan-green transition duration-300 flex items-center whitespace-nowrap px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span data-translate="home">Ahabanza</span>
                        </a>
                        <a href="#services" class="text-sm text-gray-700 hover:text-rwandan-green transition duration-300 flex items-center whitespace-nowrap px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span data-translate="services">Serivisi</span>
                        </a>
                        <a href="#about" class="text-sm text-gray-700 hover:text-rwandan-green transition duration-300 flex items-center whitespace-nowrap px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span data-translate="about">Ibyerekeye</span>
                        </a>
                        <a href="#contact" class="text-sm text-gray-700 hover:text-rwandan-green transition duration-300 flex items-center whitespace-nowrap px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span data-translate="contact">Twandikire</span>
                        </a>
                    </div>

                    
                    <div class="flex items-center space-x-4">

                        
                        <button class="bg-rwandan-blue text-white px-3 py-2 rounded-full text-sm font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300 flex items-center space-x-1 whitespace-nowrap open-ticket-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <span data-translate="submit_complaint">Ohereza Ikibazo</span>
                        </button>

                        
                        <button id="track-status-btn" class="bg-rwandan-yellow text-white px-3 py-2 rounded-full text-sm font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300 hidden md:flex items-center space-x-1 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span data-translate="track_status">Kureba Aho Bigeze</span>
                        </button>

                        <a href="#" class="hidden md:inline-flex items-center space-x-2 bg-rwandan-green hover:bg-green-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition duration-300 open-login-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span data-translate="login">Injira</span>
                        </a>

                    </div>

                    
                    <div class="md:hidden flex items-center">
                        <button class="mobile-menu-button text-gray-700 hover:text-rwandan-green">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="mobile-menu hidden md:hidden bg-white border-t border-gray-200">
                <div class="container mx-auto px-4 py-2">
                    <a href="#home" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span data-translate="home">Ahabanza</span>
                    </a>
                    <a href="#services" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span data-translate="services">Serivisi</span>
                    </a>
                    <a href="#about" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span data-translate="about">Ibyerekeye</span>
                    </a>
                    <a href="#contact" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span data-translate="contact">Twandikire</span>
                    </a>
                    <a href="#track" class="block py-2 px-4 text-rwandan-blue font-medium hover:bg-gray-100 rounded flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span data-translate="track_status">Kureba Aho Bigeze</span>
                    </a>
                    <a href="#" class="block py-2 px-4 text-rwandan-green font-medium hover:bg-gray-100 rounded flex items-center open-login-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span data-translate="login">Injira</span>
                    </a>
                </div>
            </div>
        </nav>

        
        <style>
            /* Define brand colors */
            .text-rwandan-blue {
                color: #00a0d1;
            }

            .text-rwandan-yellow {
                color: #fad201;
            }

            .text-rwandan-green {
                color: #20603d;
            }

            .bg-rwandan-blue {
                background-color: #00a0d1;
            }

            .bg-rwandan-yellow {
                background-color: #fad201;
            }

            .bg-rwandan-green {
                background-color: #20603d;
            }

            /* Logo animations */
            .floating {
                animation: float 3s ease-in-out infinite;
            }

            .sun-rays {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.7;
                animation: rotate 20s linear infinite;
            }

            @keyframes float {
                0% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-3px);
                }

                100% {
                    transform: translateY(0);
                }
            }

            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            /* Button hover effects */
            button:hover svg,
            a:hover svg {
                transform: scale(1.1);
                transition: transform 0.3s ease;
            }
        </style>


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
        
        <div class="header-gradient pt-16 pb-32 relative z-0">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    
                    <div class="md:w-1/2 text-white mb-12 md:mb-0">
                        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                            <span data-translate="hero_title">Ijwi Ryawe ni Ingirakamaro</span>
                        </h1>
                        <p class="text-lg md:text-xl mb-8 opacity-90">
                            <span data-translate="hero_description">Ikoranabuhanga rigufasha kugeza ikibazo cyawe ku bayobozi mu buryo bworoshye kandi bwihuse.</span>
                        </p>
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <button class="bg-white text-rwandan-green px-8 py-3 rounded-full font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300 flex items-center justify-center space-x-2 relative z-10 open-ticket-modal">
                                <i class="fas fa-comment-dots"></i>
                                <span data-translate="start_now">Tangira Ubu</span>
                            </button>
                            <button id="learn-more-btn" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-full font-medium hover:bg-green-200 hover:text-rwandan-green transform hover:-translate-y-1 transition duration-300 flex items-center justify-center space-x-2 relative z-10">
                                <i class="fas fa-info-circle"></i>
                                <span data-translate="learn_more">Menya Byinshi</span>
                            </button>
                        </div>

                    </div>

                    
                    <div class="md:w-1/2 flex justify-center">
                        <div class="relative w-full max-w-md">
                            
                            <svg viewBox="0 0 500 400" class="w-full h-auto">
                                
                                <circle cx="250" cy="200" r="150" fill="#ffffff" opacity="0.1" />
                                <circle cx="250" cy="200" r="120" fill="#ffffff" opacity="0.15" />

                                
                                <rect x="150" y="100" width="200" height="300" rx="20" fill="#ffffff" />
                                <rect x="160" y="120" width="180" height="240" rx="5" fill="#f0f0f0" />

                                
                                <rect x="170" y="140" width="160" height="30" rx="5" fill="#e0e0e0" />
                                <rect x="170" y="180" width="160" height="60" rx="5" fill="#00a0d1" opacity="0.7" />
                                <rect x="170" y="250" width="70" height="20" rx="5" fill="#fad201" />
                                <rect x="250" y="250" width="80" height="20" rx="5" fill="#20603d" />
                                <rect x="170" y="280" width="160" height="40" rx="5" fill="#e0e0e0" />
                                <rect x="170" y="330" width="160" height="20" rx="5" fill="#e0e0e0" />

                                
                                <circle cx="190" cy="200" r="5" fill="#ffffff" class="floating" />
                                <circle cx="210" cy="210" r="7" fill="#ffffff" class="floating" />
                                <circle cx="230" cy="195" r="6" fill="#ffffff" class="floating" />

                                
                                <path d="M370,150 C400,180 400,250 370,280" stroke="#fad201" stroke-width="5" fill="none" />
                                <path d="M390,170 C420,200 420,230 390,260" stroke="#00a0d1" stroke-width="5" fill="none" />
                                <path d="M130,150 C100,180 100,250 130,280" stroke="#20603d" stroke-width="5" fill="none" />
                                <path d="M110,170 C80,200 80,230 110,260" stroke="#fad201" stroke-width="5" fill="none" />

                                
                                <circle cx="250" cy="70" r="25" fill="#fad201" />
                                <circle cx="250" cy="70" r="20" fill="#ffffff" opacity="0.3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="absolute bottom-0 left-0 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full">
                    <path fill="#ffffff" fill-opacity="1" d="M0,128L48,133.3C96,139,192,149,288,144C384,139,480,117,576,128C672,139,768,181,864,170.7C960,160,1056,96,1152,80C1248,64,1344,96,1392,112L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>
    </header>

    
    <div class="fixed bottom-4 right-4 z-50">
        <div class="relative">
            <button id="language-selector-btn" class="flex items-center space-x-2 bg-white text-gray-700 hover:text-rwandan-blue transition duration-300 px-4 py-2 rounded-full shadow-lg">
                <i class="fas fa-globe"></i>
                <span id="current-language">Kinyarwanda</span>
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div id="language-dropdown" class="absolute right-0 bottom-12 bg-white rounded-md shadow-lg py-2 z-50 w-48 hidden">
                <a href="#" data-lang="rw" class="lang-option block px-4 py-2 text-gray-700 hover:bg-gray-100">Kinyarwanda</a>
                <a href="#" data-lang="en" class="lang-option block px-4 py-2 text-gray-700 hover:bg-gray-100">English</a>
                <a href="#" data-lang="fr" class="lang-option block px-4 py-2 text-gray-700 hover:bg-gray-100">Français</a>
            </div>
        </div>
    </div>
    <?php
    include "main.php";
    ?>
    
    <?php include 'ticket_modal.php'; ?>

    <?php include 'login_and_reg.php'; ?>
    
<?php include 'check_ticket.php'; ?>

<script src="js/modal.js"></script>
<script src="js/login_modal.js"></script>
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });

        // Language selector toggle
        document.getElementById('language-selector-btn').addEventListener('click', function() {
            document.getElementById('language-dropdown').classList.toggle('hidden');
        });

        // Track Status button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const trackStatusBtn = document.getElementById('track-status-btn');
            const mobileTrackBtn = document.querySelector('.mobile-menu a[href="#track"]');
            const checkTicketModal = document.getElementById('check-ticket-modal');
            
            if (trackStatusBtn && checkTicketModal) {
                trackStatusBtn.addEventListener('click', function() {
                    checkTicketModal.classList.remove('hidden');
                });
            }
            
            if (mobileTrackBtn && checkTicketModal) {
                mobileTrackBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    checkTicketModal.classList.remove('hidden');
                });
            }
        });

        // Language selection functionality
        document.querySelectorAll('.lang-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const lang = this.getAttribute('data-lang');
                document.getElementById('current-language').textContent = this.textContent;
                document.getElementById('language-dropdown').classList.add('hidden');
                changeLanguage(lang);
            });
        });

        // Load translations
        document.addEventListener('DOMContentLoaded', function() {
            // Default language
            loadTranslations('rw');
        });

        function changeLanguage(lang) {
            // Check if translations are already loaded
            if (window.translations) {
                applyTranslations(lang);
            } else {
                loadTranslations(lang);
            }
        }

        function loadTranslations(lang) {
            // Add the script to the page if it's not already there
            if (!document.getElementById('translations-script')) {
                const script = document.createElement('script');
                script.id = 'translations-script';
                script.src = 'translations.js';
                script.onload = function() {
                    applyTranslations(lang);
                };
                document.head.appendChild(script);
            } else {
                applyTranslations(lang);
            }
        }
        

        setTimeout(function() {
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        
        if (successMessage) {
            successMessage.style.display = 'none';
        }
        
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 5000);
    
    // Show login modal if registration was successful
    <?php if (isset($_SESSION['show_login_modal'])): ?>
    const loginModal = document.getElementById('login-modal');
    if (loginModal) {
        loginModal.classList.remove('hidden');
    }
    <?php 
    unset($_SESSION['show_login_modal']);
    endif; ?>

        function applyTranslations(lang) {
            if (!window.translations) {
                console.error('Translations not loaded');
                return;
            }

            document.querySelectorAll('[data-translate]').forEach(element => {
                const key = element.getAttribute('data-translate');
                if (window.translations[lang] && window.translations[lang][key]) {
                    element.textContent = window.translations[lang][key];
                }
            });
        }

        // Add event listener for the Learn More button
        document.addEventListener('DOMContentLoaded', function() {
            const learnMoreBtn = document.getElementById('learn-more-btn');
            if (learnMoreBtn) {
                learnMoreBtn.addEventListener('click', function() {
                    // Scroll to the about section
                    const aboutSection = document.querySelector('#services');
                    if (aboutSection) {
                        aboutSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        console.log('About section not found');
                    }
                });
            }
        });
    </script>
  
</body>

</html>
