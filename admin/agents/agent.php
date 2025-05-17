<?php
ob_start();
require_once "../../db_setup.php";
require_once "../ticket/vendor/autoload.php"; // For PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo "<script>
        alert('You must log in first as an administrator.');
        window.location.href = '../../index.php';
    </script>";
    exit();
}



// Function to send email notification
function sendEmailNotification($email, $fullName, $passCode) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ykdann53@gmail.com';
        $mail->Password   = 'kviz zxzn lkdp ccju';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('ykdann53@gmail.com', 'Citizen Engagement Portal');
        $mail->addAddress($email, $fullName);

        $link = "http://localhost/citizen/new_acc.php?code=" . urlencode($passCode);

        $htmlMessage = "
            <p>Hello,</p>
            <p>You have been invited to create an agent account on our Citizen Engagement Portal.</p>
            <p>Please click the link below to set up your account:</p>
            <p><a href='$link'>Create your account</a></p>
            <p>Your passcode: <strong>$passCode</strong></p>
            <p>This link and passcode will expire in 24 hours.</p>
            <p>Thank you,<br>Administration Team</p>
        ";

        $mail->isHTML(true);
        $mail->Subject = 'Invitation to Create Agent Account';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #3366cc;'>Agent Account Invitation</h2>
                <div style='background-color: #f5f5f5; padding: 15px; border-radius: 5px;'>$htmlMessage</div>
                <p style='font-size: 12px; color: #666; margin-top: 20px;'>This is an automated message. Please do not reply to this email.</p>
            </div>
        ";
        $mail->AltBody = strip_tags($htmlMessage);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle form submission for new agent
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'new_agent') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format";
    } else {
        $passCode = bin2hex(random_bytes(4)); // 8-char code

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO new_account_request (email, pass_code, agent_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $email, $passCode, $_SESSION['user_id']);

        if ($stmt->execute()) {
            if (sendEmailNotification($email, "", $passCode)) {
                $success_message = "Invitation sent successfully to $email";
            } else {
                $error_message = "Email not sent. Invitation saved.";
            }
        } else {
            if ($stmt->errno == 1062) {
                $error_message = "This email already has a pending invitation.";
            } else {
                $error_message = "Database error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

// Get all admins
$admins = [];
$result = $conn->query("SELECT `id`, `fullname`, `email`, `phone`, `status`, `created_at`, `updated_at` FROM `admins` ORDER BY created_at DESC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

$conn->close();

// Page title
$pageTitle = "Agent Management";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .wave-container {
            position: relative;
            overflow: hidden;
        }
        .wave {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.3;
            left: 0;
            top: 0;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.5));
            z-index: 0;
            transform: translateX(-100%);
            animation: wave 3s infinite linear;
        }
        .wave:nth-child(2) {
            animation-delay: 0.5s;
        }
        .wave:nth-child(3) {
            animation-delay: 1s;
        }
        @keyframes wave {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
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
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .progress-animation {
            animation: progressGrow 1s ease-out forwards;
            transform-origin: left;
        }
        @keyframes progressGrow {
            from {
                transform: scaleX(0);
            }
            to {
                transform: scaleX(1);
            }
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .bg-rwanda-blue {
            background-color: #0073e6;
        }
        .bg-rwanda-green {
            background-color: #00a651;
        }
        .bg-rwanda-yellow {
            background-color: #ffc72c;
        }
        .text-rwanda-blue {
            color: #0073e6;
        }
        .text-rwanda-green {
            color: #00a651;
        }
        .text-rwanda-yellow {
            color: #ffc72c;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

       
            <!-- Main content area -->
            <div class="flex-1 overflow-auto p-6">
                <div class="row mb-4">
                    <div class="col">
                        <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAgentModal">
                            <i class="fas fa-user-plus me-2"></i>Add New Agent
                        </button>
                    </div>
                </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $success_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Agents Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Agents List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="agentsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($admins as $admin): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($admin['id']) ?></td>
                                            <td><?= htmlspecialchars($admin['fullname']) ?></td>
                                            <td><?= htmlspecialchars($admin['email']) ?></td>
                                            <td><?= htmlspecialchars($admin['phone']) ?></td>
                                            <td>
                                                <?php if ($admin['status'] == 'approved'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($admin['created_at']) ?></td>
                                            <td><?= htmlspecialchars($admin['updated_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>

    <!-- Add Agent Modal -->
    <div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgentModalLabel">Invite New Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="form-text">An invitation will be sent to this email address.</div>
                        </div>
                        <input type="hidden" name="action" value="new_agent">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Invitation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#agentsTable').DataTable({
                order: [[0, 'desc']]
            });
        });
    </script>
</body>
</html>