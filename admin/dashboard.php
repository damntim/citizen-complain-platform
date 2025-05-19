<?php

require_once '../db_setup.php';



$currentWeekStart = date("Y-m-d", strtotime("monday this week"));
$lastWeekStart = date("Y-m-d", strtotime("monday last week"));
$lastWeekEnd = date("Y-m-d", strtotime("sunday last week"));


$queryCurrent = "SELECT COUNT(*) AS current_count FROM tickets WHERE status = 'ongoing' AND created_at >= ?";
$stmtCurrent = mysqli_prepare($conn, $queryCurrent);
mysqli_stmt_bind_param($stmtCurrent, "s", $currentWeekStart);
mysqli_stmt_execute($stmtCurrent);
mysqli_stmt_bind_result($stmtCurrent, $currentCount);
mysqli_stmt_fetch($stmtCurrent);
mysqli_stmt_close($stmtCurrent);


$queryLast = "SELECT COUNT(*) AS last_count FROM tickets WHERE status = 'ongoing' AND created_at BETWEEN ? AND ?";
$stmtLast = mysqli_prepare($conn, $queryLast);
mysqli_stmt_bind_param($stmtLast, "ss", $lastWeekStart, $lastWeekEnd);
mysqli_stmt_execute($stmtLast);
mysqli_stmt_bind_result($stmtLast, $lastCount);
mysqli_stmt_fetch($stmtLast);
mysqli_stmt_close($stmtLast);


$percentageChange = 0;
$arrow = 'up';
$changeColor = 'green';

if ($lastCount > 0) {
    $percentageChange = (($currentCount - $lastCount) / $lastCount) * 100;
    $percentageChange = round($percentageChange, 1);

    if ($percentageChange < 0) {
        $arrow = 'down';
        $changeColor = 'red';
    }
} else {
    $percentageChange = 100;
}


$currentWeekStart = date("Y-m-d", strtotime("monday this week"));
$lastWeekStart = date("Y-m-d", strtotime("monday last week"));
$lastWeekEnd = date("Y-m-d", strtotime("sunday last week"));


$querySolvedCurrent = "SELECT COUNT(*) AS current_solved FROM tickets WHERE decision = 'solved' AND completed_at >= ?";
$stmtSolvedCurrent = mysqli_prepare($conn, $querySolvedCurrent);
mysqli_stmt_bind_param($stmtSolvedCurrent, "s", $currentWeekStart);
mysqli_stmt_execute($stmtSolvedCurrent);
mysqli_stmt_bind_result($stmtSolvedCurrent, $currentSolved);
mysqli_stmt_fetch($stmtSolvedCurrent);
mysqli_stmt_close($stmtSolvedCurrent);


$querySolvedLast = "SELECT COUNT(*) AS last_solved FROM tickets WHERE decision = 'solved' AND completed_at BETWEEN ? AND ?";
$stmtSolvedLast = mysqli_prepare($conn, $querySolvedLast);
mysqli_stmt_bind_param($stmtSolvedLast, "ss", $lastWeekStart, $lastWeekEnd);
mysqli_stmt_execute($stmtSolvedLast);
mysqli_stmt_bind_result($stmtSolvedLast, $lastSolved);
mysqli_stmt_fetch($stmtSolvedLast);
mysqli_stmt_close($stmtSolvedLast);


$solvedPercentageChange = 0;
$solvedArrow = 'up';
$solvedChangeColor = 'green';

if ($lastSolved > 0) {
    $solvedPercentageChange = (($currentSolved - $lastSolved) / $lastSolved) * 100;
    $solvedPercentageChange = round($solvedPercentageChange, 1);
    if ($solvedPercentageChange < 0) {
        $solvedArrow = 'down';
        $solvedChangeColor = 'red';
    }
} else {
    $solvedPercentageChange = 100;
}


$currentWeekStart = date("Y-m-d", strtotime("monday this week"));
$lastWeekStart = date("Y-m-d", strtotime("monday last week"));
$lastWeekEnd = date("Y-m-d", strtotime("sunday last week"));


function getAvgResponseTime($conn, $startDate, $endDate = null)
{
    $sql = "SELECT TIMESTAMPDIFF(SECOND, t.created_at, rt.first_response_time) AS response_time
            FROM tickets t
            LEFT JOIN (
                SELECT ticket_id, MIN(created_at) AS first_response_time
                FROM response_ticket
                GROUP BY ticket_id
            ) rt ON t.id = rt.ticket_id
            WHERE t.created_at >= ?" . ($endDate ? " AND t.created_at <= ?" : "") . "
              AND rt.first_response_time IS NOT NULL";

    $stmt = $endDate ? mysqli_prepare($conn, $sql) : mysqli_prepare($conn, $sql);
    if ($endDate) {
        mysqli_stmt_bind_param($stmt, "ss", $startDate, $endDate);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $startDate);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $totalSeconds = 0;
    $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $totalSeconds += $row['response_time'];
        $count++;
    }

    return $count > 0 ? round($totalSeconds / $count / 3600, 2) : 0;
}


$avgResponseThisWeek = getAvgResponseTime($conn, $currentWeekStart);
$avgResponseLastWeek = getAvgResponseTime($conn, $lastWeekStart, $lastWeekEnd);


$responseArrow = 'up';
$responseColor = 'red';
$responseChange = 0;

if ($avgResponseLastWeek > 0) {
    $responseChange = round((($avgResponseThisWeek - $avgResponseLastWeek) / $avgResponseLastWeek) * 100, 1);
    if ($responseChange < 0) {
        $responseArrow = 'down';
        $responseColor = 'green';
    }
}



$currentWeekStart = date("Y-m-d", strtotime("monday this week"));
$lastWeekStart = date("Y-m-d", strtotime("monday last week"));
$lastWeekEnd = date("Y-m-d", strtotime("sunday last week"));

function getSatisfactionRate($conn, $startDate, $endDate = null)
{
    $queryTotal = "SELECT COUNT(*) AS total FROM tickets WHERE created_at >= ?" . ($endDate ? " AND created_at <= ?" : "");
    $querySatisfied = "SELECT COUNT(*) AS satisfied FROM tickets WHERE citizen_remark = 'satisfied' AND created_at >= ?" . ($endDate ? " AND created_at <= ?" : "");

    if ($endDate) {
        $stmtTotal = mysqli_prepare($conn, $queryTotal);
        mysqli_stmt_bind_param($stmtTotal, "ss", $startDate, $endDate);
        $stmtSat = mysqli_prepare($conn, $querySatisfied);
        mysqli_stmt_bind_param($stmtSat, "ss", $startDate, $endDate);
    } else {
        $stmtTotal = mysqli_prepare($conn, $queryTotal);
        mysqli_stmt_bind_param($stmtTotal, "s", $startDate);
        $stmtSat = mysqli_prepare($conn, $querySatisfied);
        mysqli_stmt_bind_param($stmtSat, "s", $startDate);
    }

    mysqli_stmt_execute($stmtTotal);
    $resultTotal = mysqli_stmt_get_result($stmtTotal);
    $total = mysqli_fetch_assoc($resultTotal)['total'];

    mysqli_stmt_execute($stmtSat);
    $resultSat = mysqli_stmt_get_result($stmtSat);
    $satisfied = mysqli_fetch_assoc($resultSat)['satisfied'];

    return $total > 0 ? round(($satisfied / $total) * 100, 1) : 0;
}


$satisfactionThisWeek = getSatisfactionRate($conn, $currentWeekStart);
$satisfactionLastWeek = getSatisfactionRate($conn, $lastWeekStart, $lastWeekEnd);


$satArrow = 'up';
$satColor = 'green';
$satChange = 0;

if ($satisfactionLastWeek > 0) {
    $satChange = round(($satisfactionThisWeek - $satisfactionLastWeek), 1);
    if ($satChange < 0) {
        $satArrow = 'down';
        $satColor = 'red';
    }
}

$institutions = [];

$sql = "
    SELECT i.id, i.name, COUNT(t.service) AS service_count
    FROM institutions i
    JOIN services s ON i.id = s.institution_id
    LEFT JOIN tickets t ON s.id = t.service
    GROUP BY i.id
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $institutions[] = $row;
    }
}
$total = 0;

$totalResult = $conn->query("SELECT COUNT(service) as total_services FROM tickets");

if ($totalResult) {
    $row = $totalResult->fetch_assoc();
    $total = $row['total_services'];
}

$sql = "SELECT 
            t.id, 
            t.ticket_number, 
            t.subject, 
            t.status, 
            i.name AS instutition_name, 
            t.created_at, 
            t.institution
        FROM tickets t
        JOIN institutions i ON t.institution = i.id
        ORDER BY t.created_at DESC
        LIMIT 5";

$result5 = $conn->query($sql);


?>


<div class="flex h-screen overflow-hidden">
    
    <?php
    include 'sidebar.php'
    ?>

    
    <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-100">
        
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-800">Admin Dashboard</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="p-1 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                        <button class="p-1 text-gray-500 hover:text-gray-700 relative">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-red-500"></span>
                        </button>
                        <button class="hidden md:block p-1 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-cog text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        
        <div class="p-4 sm:p-6 lg:p-8">
            
            <div class="mb-6 fade-in">
                <h2 class="text-2xl font-bold text-gray-800">Welcome back, <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?></h2>
                <p class="text-gray-600">Here's what's happening with the Citizen Engagement System today.</p>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                
                <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-1">
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Active Tickets</h3>
                            <span class="p-2 rounded-full bg-rwanda-blue bg-opacity-10">
                                <i class="fas fa-ticket-alt text-rwanda-blue"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold"><?= $currentCount ?></p>
                        <div class="flex items-center mt-2">
                            <span class="text-<?= $changeColor ?>-500 text-sm flex items-center">
                                <i class="fas fa-arrow-<?= $arrow ?> mr-1"></i> <?= $percentageChange ?>%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">From last week</span>
                        </div>
                    </div>
                </div>


                
                <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-2">
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Resolved</h3>
                            <span class="p-2 rounded-full bg-rwanda-green bg-opacity-10">
                                <i class="fas fa-check-circle text-rwanda-green"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold"><?= $currentSolved ?></p>
                        <div class="flex items-center mt-2">
                            <span class="text-<?= $solvedChangeColor ?>-500 text-sm flex items-center">
                                <i class="fas fa-arrow-<?= $solvedArrow ?> mr-1"></i> <?= $solvedPercentageChange ?>%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">From last week</span>
                        </div>
                    </div>
                </div>


                
                <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-3">
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Avg. Response</h3>
                            <span class="p-2 rounded-full bg-rwanda-yellow bg-opacity-10">
                                <i class="fas fa-clock text-rwanda-yellow"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold"><?= $avgResponseThisWeek ?> hrs</p>
                        <div class="flex items-center mt-2">
                            <span class="text-<?= $responseColor ?>-500 text-sm flex items-center">
                                <i class="fas fa-arrow-<?= $responseArrow ?> mr-1"></i> <?= abs($responseChange) ?>%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">From last week</span>
                        </div>
                    </div>
                </div>


                
                <div class="bg-white rounded-lg shadow-sm p-6 wave-container fade-in fade-in-delay-4">
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="wave"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Satisfaction</h3>
                            <span class="p-2 rounded-full bg-rwanda-blue bg-opacity-10">
                                <i class="fas fa-smile text-rwanda-blue"></i>
                            </span>
                        </div>
                        <p class="text-3xl font-bold"><?= $satisfactionThisWeek ?>%</p>
                        <div class="flex items-center mt-2">
                            <span class="text-<?= $satColor ?>-500 text-sm flex items-center">
                                <i class="fas fa-arrow-<?= $satArrow ?> mr-1"></i> <?= abs($satChange) ?>%
                            </span>
                            <span class="text-gray-500 text-sm ml-2">From last week</span>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                
                <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2 fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Ticket Analytics</h3>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Day</button>
                            <button class="px-3 py-1 text-sm bg-rwanda-blue text-white rounded-md">Week</button>
                            <button class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Month</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="ticketsChart"></canvas>
                    </div>
                </div>

                
                <?php

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "citizen_portal";

                $db = mysqli_connect($servername, $username, $password, $dbname);
                if (!$db) {
                    die("Database connection failed: " . mysqli_connect_error());
                }


                $sql = "
    SELECT 
      
    t.id,
    t.ticket_number,
    t.full_name,
    t.created_at,
    t.updated_at,
    t.completed_at,
    t.status,
    t.decision,
    t.agent_on,
    t.citizen_remark,
    i.name AS institution_name,
    s.name_en AS service_name,
    a.fullname AS agent_name
FROM tickets t
LEFT JOIN institutions i ON t.institution = i.id
LEFT JOIN services s ON t.service = s.id
LEFT JOIN admins a ON t.agent_on = a.id
WHERE t.created_at >= NOW() - INTERVAL 1 DAY
ORDER BY t.updated_at DESC
LIMIT 5

";

                $result = mysqli_query($db, $sql);
                if (!$result) {
                    die("Query failed: " . mysqli_error($db));
                }

                $activities = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $activities[] = $row;
                }
                mysqli_free_result($result);


                function timeAgo($datetime)
                {
                    $now = new DateTime();
                    $past = new DateTime($datetime);
                    $interval = $now->diff($past);

                    if ($interval->h >= 1) {
                        return $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
                    } elseif ($interval->i >= 1) {
                        return $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
                    } else {
                        return "just now";
                    }
                }
                ?>

                <div class="bg-white rounded-lg shadow-sm p-6 fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                        <button class="text-rwanda-blue hover:underline text-sm">View All</button>
                    </div>
                    <div class="space-y-4">
                        <?php foreach ($activities as $activity): ?>
                            <?php

                            $icon = '';
                            $icon_color = '';
                            $bg_color = '';
                            $description = '';

                            if (!empty($activity['completed_at']) && $activity['decision'] == 'solved') {

                                $icon = 'fas fa-check';
                                $icon_color = 'text-rwanda-green';
                                $bg_color = 'bg-rwanda-green bg-opacity-10';
                                $description = "<span class='font-semibold'>" . htmlspecialchars($activity['full_name']) . "</span> resolved ticket <span class='font-semibold'>#" . htmlspecialchars($activity['ticket_number']) . "</span>";
                            } elseif (!empty($activity['citizen_remark']) && $activity['updated_at'] > $activity['created_at']) {

                                $icon = 'fas fa-comment';
                                $icon_color = 'text-rwanda-yellow';
                                $bg_color = 'bg-rwanda-yellow bg-opacity-10';
                                $description = "<span class='font-semibold'>" . htmlspecialchars($activity['full_name']) . "</span> commented on ticket <span class='font-semibold'>#" . htmlspecialchars($activity['ticket_number']) . "</span>";
                            } elseif (!empty($activity['agent_name'])) {

                                $icon = 'fas fa-user-plus';
                                $icon_color = 'text-purple-500';
                                $bg_color = 'bg-purple-100';
                                $description = "<span class='font-semibold'>" . htmlspecialchars($activity['agent_name']) . "</span> assigned to " . htmlspecialchars($activity['institution_name'] ?: 'team');
                            } else {

                                $icon = 'fas fa-ticket-alt';
                                $icon_color = 'text-rwanda-blue';
                                $bg_color = 'bg-rwanda-blue bg-opacity-10';
                                $description = "New ticket <span class='font-semibold'>#" . htmlspecialchars($activity['ticket_number']) . "</span> submitted by <span class='font-semibold'>" . htmlspecialchars($activity['full_name']) . "</span>";
                            }
                            ?>
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full <?php echo $bg_color; ?> flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="<?php echo $icon; ?> text-sm <?php echo $icon_color; ?>"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800"><?php echo $description; ?></p>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo timeAgo($activity['updated_at']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php

                mysqli_close($db);
                ?>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                
                <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2 fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Tickets</h3>
                        <a href="all_tickets.php" class="text-rwanda-blue hover:underline text-sm">View All Tickets</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Insutition</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($result5->num_rows > 0): ?>
                                    <?php while ($row = $result5->fetch_assoc()): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?php echo htmlspecialchars($row['ticket_number']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($row['instutition_name']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    <?php echo htmlspecialchars($row['subject']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php
                                                $status = $row['status'];
                                                $bgColor = 'bg-gray-100 text-gray-800';
                                                if ($status === 'new') $bgColor = 'bg-yellow-100 text-yellow-800';
                                                elseif ($status === 'ongoing') $bgColor = 'bg-blue-100 text-blue-800';
                                                elseif ($status === 'completed') $bgColor = 'bg-green-100 text-green-800';
                                                ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $bgColor; ?>">
                                                    <?php echo htmlspecialchars($status); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>

                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No tickets found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-white rounded-lg shadow-sm p-6 fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Institutions</h3>
                        <button class="text-rwanda-blue hover:underline text-sm">Details</button>
                    </div>
                    <div class="space-y-4">
                        <?php foreach ($institutions as $inst):
                            $percentage = $total > 0 ? round(($inst['service_count'] / $total) * 100) : 0;
                        ?>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($inst['name']) ?></span>
                                    <span class="text-sm font-medium text-gray-700"><?= $percentage ?>%</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="bg-rwanda-blue h-full rounded-full progress-animation" style="width: <?= $percentage ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>


        </div>
    </main>
</div>