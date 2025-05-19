<aside class="hidden md:flex flex-col w-64 bg-white shadow-lg">
    
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
    <br><br>
    
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

    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4">
            <div class="space-y-2">
                <a href="" class="flex items-center px-4 py-3 text-gray-800 bg-gray-100 rounded-lg">
                    <i class="fas fa-tachometer-alt mr-3 text-rwanda-blue"></i>
                    <span>Dashboard</span>
                </a>
                <a href="ticket/" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-ticket-alt mr-3 text-rwanda-green"></i>
                    <span>Tickets</span>
                </a>
                <a href="agents/" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-users mr-3 text-rwanda-yellow"></i>
                    <span>Agents</span>
                </a>
                <a href="instutitions/" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-university mr-3 text-rwanda-blue"></i>
                    <span>Institution & Services </span>
                </a>

            </div>



        </nav>
    </div>

    <div class="p-4 border-t">
    <div class="flex items-center">
            <img src="../uploads/profiles/<?php echo isset($_SESSION['user_image']) && !empty($_SESSION['user_image']) ? $_SESSION['user_image'] : '/api/placeholder/40/40'; ?>" alt="Profile" class="w-10 h-10 rounded-full border-2 border-rwanda-blue">
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-800"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
            <a href="../logout.php" class="ml-auto text-gray-500 hover:text-gray-700">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</aside>


<div class="md:hidden fixed bottom-4 right-4 z-50">
    <button id="mobile-menu-toggle" class="p-3 rounded-full bg-rwanda-blue text-white shadow-lg">
        <i class="fas fa-bars"></i>
    </button>
</div>


<div id="mobile-menu-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden"></div>


<div id="mobile-menu" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300">
    
    
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

        <button id="mobile-menu-close" class="ml-auto text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

<br><br>

    <div class="flex-1 overflow-y-auto py-4">
        <nav class="px-4">
            <div class="space-y-2">
                <a href="" class="flex items-center px-4 py-3 text-gray-800 bg-gray-100 rounded-lg">
                    <i class="fas fa-tachometer-alt mr-3 text-rwanda-blue"></i>
                    <span>Dashboard</span>
                </a>
                <a href="ticket/" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-ticket-alt mr-3 text-rwanda-green"></i>
                    <span>Tickets</span>
                </a>
                <a href="agents/" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-users mr-3 text-rwanda-yellow"></i>
                    <span>Agents</span>
                </a>
                <a href="instutitions/" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-university mr-3 text-rwanda-blue"></i>
                    <span>Institution & Services </span>
                </a>
            </div>
        </nav>
    </div>
    <div class="fixed bottom-0 left-0 w-full p-4 border-t bg-white z-50">
        <div class="flex items-center">
            <img src="../uploads/profiles/<?php echo isset($_SESSION['user_image']) && !empty($_SESSION['user_image']) ? $_SESSION['user_image'] : '/api/placeholder/40/40'; ?>" alt="Profile" class="w-10 h-10 rounded-full border-2 border-rwanda-blue">
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-800"><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
            <a href="../logout.php" class="ml-auto text-gray-500 hover:text-gray-700">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</div>