<?php
// ================== Database Connection ==================
$servername = "localhost";
$username = "root";
$password = "";
$database = "todomanager";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// ================== Current Month Filter ==================
$currentMonth = date('m');
$currentYear = date('Y');

// Fetch total tasks of current month
$totalQuery = "SELECT COUNT(*) as total FROM todo WHERE MONTH(duedate) = '$currentMonth' AND YEAR(duedate) = '$currentYear'";
$totalResult = $conn->query($totalQuery);
$totalTasks = $totalResult->fetch_assoc()['total'] ?? 0;

// Fetch completed tasks
$doneQuery = "SELECT COUNT(*) as done FROM todo WHERE status = 'done' AND MONTH(duedate) = '$currentMonth' AND YEAR(duedate) = '$currentYear'";
$doneResult = $conn->query($doneQuery);
$doneTasks = $doneResult->fetch_assoc()['done'] ?? 0;

// Fetch pending tasks (tasks that are not marked as 'done')
$pendingQuery = "SELECT COUNT(*) as pending FROM todo WHERE (status != 'done' OR status IS NULL) AND MONTH(duedate) = '$currentMonth' AND YEAR(duedate) = '$currentYear'";
$pendingResult = $conn->query($pendingQuery);
$pendingTasks = $pendingResult->fetch_assoc()['pending'] ?? 0;

// ================== Calculate Performance ==================
$completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100, 2) : 0;

if ($completionRate >= 90) {
    $performance = "🌟 Excellent";
} elseif ($completionRate >= 70) {
    $performance = "💪 Good";
} elseif ($completionRate >= 40) {
    $performance = "⚙️ Average";
} else {
    $performance = "🔴 Poor";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Todo Report</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f6f9fc;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 15px;
            color: #333;
        }

        h1 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 15px;
            text-align: center;
        }

        .report-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }

        .card {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            width: 160px;
            text-align: center;
            padding: 15px 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card h2 {
            font-size: 28px;
            color: #3498db;
            margin: 8px 0;
        }

        .card p {
            font-size: 14px;
            color: #555;
            margin: 0;
        }

        footer {
            margin-top: 30px;
            color: #777;
            font-size: 13px;
            text-align: center;
        }

        .no-data {
            color: #888;
            margin-top: 50px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <link rel="stylesheet" href="style.css">
    <a href="home.php" class="homeUI-btn">Home</a>
    <a href="logout.php" class="logout-btn">Logout</a>

    <h1>📊 Monthly Todo Report</h1>

    <?php if ($totalTasks > 0): ?>
        <div class="report-container">
            <div class="card">
                <h2>📋 <?= $totalTasks ?></h2>
                <p>Total</p>
            </div>
            <div class="card">
                <h2>✅ <?= $doneTasks ?></h2>
                <p>Completed</p>
            </div>
            <div class="card">
                <h2>🕓 <?= $pendingTasks ?></h2>
                <p>Pending</p>
            </div>
            <div class="card">
                <h2>📈 <?= $completionRate ?>%</h2>
                <p>Completion</p>
            </div>
            <div class="card">
                <h2><?= $performance ?></h2>
                <p>Performance</p>
            </div>
        </div>
    <?php else: ?>
        <div class="no-data">No task data found for this month.</div>
    <?php endif; ?>

    <footer>
        Report generated for <?= date('F Y'); ?>
    </footer>
</body>

</html>