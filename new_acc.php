<?php
require_once "db_setup.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$showForm = false;
$errorMessage = "";
$successMessage = "";
$requestData = null;

// Check if code is provided in URL
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $code = $_GET['code'];
    
    // Verify the code exists and is valid
    $stmt = $conn->prepare("SELECT * FROM new_account_request WHERE pass_code = ? AND used = 0");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $requestData = $result->fetch_assoc();
    
    if ($requestData) {
        // Check if the request has expired (24 hours)
        $createdTime = strtotime($requestData['created_at']);
        $currentTime = time();
        $timeDiff = $currentTime - $createdTime;
        $hoursDiff = $timeDiff / 3600; // Convert seconds to hours
        
        if ($hoursDiff <= 24) {
            $showForm = true;
        } else {
            $errorMessage = "This invitation has expired. Please request a new invitation.";
        }
    } else {
        $errorMessage = "Invalid or already used invitation code. Please check your email or contact an administrator.";
    }
} else {
    $errorMessage = "No invitation code provided. Please use the link from your invitation email.";
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register']) && $showForm) {
    // Validate form inputs
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    
    // Basic validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } elseif ($password !== $confirmPassword) {
        $errorMessage = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $errorMessage = "Password must be at least 8 characters long.";
    } else {
        // Check if the email matches the invitation
        if ($email !== $requestData['email']) {
            $errorMessage = "The email address does not match the invitation.";
        } else {
            // Process profile image upload
            $profile_image = null;
            $upload_dir = "uploads/profiles/";
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed_types = ['image/jpeg','image/jpg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($_FILES['profile_image']['type'], $allowed_types)) {
                    $errorMessage = "Only JPG, PNG, and GIF images are allowed.";
                } elseif ($_FILES['profile_image']['size'] > $max_size) {
                    $errorMessage = "Image size should not exceed 5MB.";
                } else {
                    $file_extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                    $profile_image = uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $profile_image;
                    
                    if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        $errorMessage = "Failed to upload image. Please try again.";
                        $profile_image = null;
                    }
                }
            }
            
            if (empty($errorMessage)) {
                // Start transaction
                $conn->begin_transaction();
                
                try {
                    // Hash the password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert new admin
                    $stmt = $conn->prepare("INSERT INTO admins (fullname, email, phone, password, profile_image, status) VALUES (?, ?, ?, ?, ?, 'approved')");
                    $stmt->bind_param("sssss", $fullname, $email, $phone, $hashedPassword, $profile_image);
                    $stmt->execute();
                    
                    // Mark the invitation as used
                    $stmt = $conn->prepare("UPDATE new_account_request SET used = 1 WHERE pass_code = ?");
                    $stmt->bind_param("s", $code);
                    $stmt->execute();
                    
                    // Commit transaction
                    $conn->commit();
                    
                    $successMessage = "Your account has been created successfully. You can now log in.";
                    $showForm = false;
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $conn->rollback();
                    
                    // Delete uploaded image if there was an error
                    if ($profile_image && file_exists($upload_path)) {
                        unlink($upload_path);
                    }
                    
                    if ($conn->errno == 1062) { // Duplicate entry error
                        $errorMessage = "An account with this email already exists.";
                    } else {
                        $errorMessage = "An error occurred while creating your account. Please try again later.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-height: 100px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            color: #6c757d;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 2px;
        }
        .profile-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            display: block;
            border: 2px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="logo-container">
                <!-- Replace with your logo -->
                <img src="../assets/img/logo.png" alt="Logo" class="logo">
            </div>
            
            <h2 class="form-title">Create Your Account</h2>
            
            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                    <div class="text-center mt-3">
                        <a href="index.php" class="btn btn-primary">Go to Login</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($showForm): ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3 text-center">
                        <img src="../assets/img/default-profile.png" alt="Profile Preview" class="profile-preview" id="profile-preview">
                        <label for="profile_image" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                        <div class="form-text">Upload a profile picture (optional). Max size: 5MB.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($requestData['email']); ?>" readonly>
                        <div class="form-text">This email address is linked to your invitation and cannot be changed.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <i class="toggle-password fas fa-eye" data-target="password"></i>
                        </div>
                        <div class="password-strength bg-secondary" id="password-strength"></div>
                        <div class="form-text" id="password-feedback">Password must be at least 8 characters long.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <i class="toggle-password fas fa-eye" data-target="confirm_password"></i>
                        </div>
                        <div class="form-text" id="password-match-feedback"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="register" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            <?php elseif (empty($successMessage)): ?>
                <div class="text-center">
                    <p>If you believe this is an error, please contact your administrator or request a new invitation.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password').click(function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Password strength indicator
            $('#password').on('input', function() {
                const password = $(this).val();
                let strength = 0;
                let feedback = '';
                
                if (password.length >= 8) {
                    strength += 25;
                    feedback = 'Minimum length met. ';
                } else {
                    feedback = 'Password must be at least 8 characters. ';
                }
                
                if (password.match(/[A-Z]/)) {
                    strength += 25;
                    feedback += 'Has uppercase. ';
                }
                
                if (password.match(/[0-9]/)) {
                    strength += 25;
                    feedback += 'Has number. ';
                }
                
                if (password.match(/[^A-Za-z0-9]/)) {
                    strength += 25;
                    feedback += 'Has special character. ';
                }
                
                let color;
                if (strength < 25) color = '#dc3545'; // red
                else if (strength < 50) color = '#ffc107'; // yellow
                else if (strength < 75) color = '#fd7e14'; // orange
                else color = '#28a745'; // green
                
                $('#password-strength').css('width', strength + '%').css('background-color', color);
                $('#password-feedback').text(feedback);
            });
            
            // Password match indicator
            $('#confirm_password').on('input', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();
                
                if (password === confirmPassword) {
                    $('#password-match-feedback').text('Passwords match').css('color', '#28a745');
                } else {
                    $('#password-match-feedback').text('Passwords do not match').css('color', '#dc3545');
                }
            });
            
            // Profile image preview
            $('#profile_image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>