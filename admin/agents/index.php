

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rwanda Citizen Engagement - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome Icons (latest, one source only) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --rwanda-blue: #00a0e9;
            --rwanda-yellow: #fad201;
            --rwanda-green: #00a651;
        }

        .bg-rwanda-blue {
            background-color: var(--rwanda-blue);
        }

        .bg-rwanda-yellow {
            background-color: var(--rwanda-yellow);
        }

        .bg-rwanda-green {
            background-color: var(--rwanda-green);
        }

        .text-rwanda-blue {
            color: var(--rwanda-blue);
        }

        .text-rwanda-yellow {
            color: var(--rwanda-yellow);
        }

        .text-rwanda-green {
            color: var(--rwanda-green);
        }

        .border-rwanda-blue {
            border-color: var(--rwanda-blue);
        }

        .border-rwanda-yellow {
            border-color: var(--rwanda-yellow);
        }

        .border-rwanda-green {
            border-color: var(--rwanda-green);
        }

        .nav-link {
            position: relative;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--rwanda-yellow);
        }

        .ticket-card {
            transition: all 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        #globe-container {
            width: 100%;
            height: 200px;
            position: relative;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .fade-in-delay-1 {
            animation-delay: 0.1s;
        }

        .fade-in-delay-2 {
            animation-delay: 0.2s;
        }

        .fade-in-delay-3 {
            animation-delay: 0.3s;
        }

        .fade-in-delay-4 {
            animation-delay: 0.4s;
        }

        /* Progress Bar Animation */
        @keyframes progress {
            0% {
                width: 0;
            }

            100% {
                width: 100%;
            }
        }

        .progress-animation {
            animation: progress 1.5s ease-out forwards;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--rwanda-blue);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #0088cc;
        }

        /* Wave effect */
        .wave-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            height: 100%;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: scale(1.5);
            animation: wave 8s infinite linear;
        }

        .wave:nth-child(2) {
            bottom: -5%;
            animation-delay: 0.5s;
            opacity: 0.6;
        }

        .wave:nth-child(3) {
            bottom: -10%;
            animation-delay: 1s;
            opacity: 0.4;
        }

        @keyframes wave {
            0% {
                transform: scale(1.5) translateX(-10%) rotate(0deg);
            }

            100% {
                transform: scale(1.5) translateX(10%) rotate(360deg);
            }
        }
    </style>
</head>
<?php
include 'agent.php';
?>


<body class="bg-gray-100 font-sans">
   

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script>
        // Mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.remove('-translate-x-full');
                mobileMenuOverlay.classList.remove('hidden');
            });

            mobileMenuClose.addEventListener('click', function() {
                mobileMenu.classList.add('-translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
            });

            mobileMenuOverlay.addEventListener('click', function() {
                mobileMenu.classList.add('-translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
            });
        });

        // Chart.js - Tickets Analytics
        const ctx = document.getElementById('ticketsChart').getContext('2d');
        const ticketsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'New Tickets',
                    data: [12, 19, 15, 8, 22, 14, 10],
                    backgroundColor: 'rgba(0, 160, 233, 0.2)',
                    borderColor: 'rgba(0, 160, 233, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(0, 160, 233, 1)',
                    pointRadius: 4
                }, {
                    label: 'Resolved Tickets',
                    data: [8, 15, 12, 6, 18, 11, 8],
                    backgroundColor: 'rgba(0, 166, 81, 0.2)',
                    borderColor: 'rgba(0, 166, 81, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(0, 166, 81, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Simple 3D globe visualization
        function initGlobe() {
            const container = document.getElementById('globe-container');
            
            // Create scene
            const scene = new THREE.Scene();
            
            // Create camera
            const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.z = 2;
            
            // Create renderer
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);
            
            // Create sphere (globe)
            const geometry = new THREE.SphereGeometry(1, 32, 32);
            const material = new THREE.MeshBasicMaterial({
                color: 0x00a0e9,
                wireframe: true,
                transparent: true,
                opacity: 0.6
            });
            const globe = new THREE.Mesh(geometry, material);
            scene.add(globe);
            
            // Add points to represent ticket locations
            const pointsGeometry = new THREE.BufferGeometry();
            const pointsMaterial = new THREE.PointsMaterial({
                color: 0xfad201,
                size: 0.05
            });
            
            // Generate random points on the sphere
            const pointsCount = 50;
            const positions = new Float32Array(pointsCount * 3);
            
            for (let i = 0; i < pointsCount; i++) {
                const phi = Math.acos(-1 + (2 * i) / pointsCount);
                const theta = Math.sqrt(pointsCount * Math.PI) * phi;
                
                positions[i * 3] = Math.cos(theta) * Math.sin(phi);
                positions[i * 3 + 1] = Math.sin(theta) * Math.sin(phi);
                positions[i * 3 + 2] = Math.cos(phi);
            }
            
            pointsGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            const points = new THREE.Points(pointsGeometry, pointsMaterial);
            scene.add(points);
            
            // Animation
            function animate() {
                requestAnimationFrame(animate);
                
                globe.rotation.y += 0.005;
                points.rotation.y += 0.005;
                
                renderer.render(scene, camera);
            }
            
            animate();
            
            // Handle window resize
            window.addEventListener('resize', function() {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            });
        }
        
        // Initialize globe when the page is loaded
        window.addEventListener('load', initGlobe);
    </script>
</body>

</html>