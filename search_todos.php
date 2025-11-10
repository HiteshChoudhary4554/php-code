<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    exit('Unauthorized');
}

if (!isset($_GET['query'])) {
    http_response_code(400);
    exit('Missing query parameter');
}

$query = trim($_GET['query']);
if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todomanager";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    exit('Database connection failed');
}

// Detect category column name
$catCol = null;
$colRes = $conn->query("SHOW COLUMNS FROM todo");
if ($colRes) {
    while ($c = $colRes->fetch_assoc()) {
        $field = $c['Field'];
        $norm = preg_replace('/[^a-z0-9]/', '', strtolower($field));
        if (strpos($norm, 'category') !== false || strpos($norm, 'cat') !== false) {
            $catCol = $field;
            break;
        }
    }
}

// Build search query with JOIN if we have category column
$searchQuery = "SELECT t.id, t.title, t.description, t.duedate, t.status";
if ($catCol) {
    $searchQuery .= ", c.category as category_name FROM todo t LEFT JOIN category c ON t.`" . $catCol . "` = c.id";
} else {
    $searchQuery .= " FROM todo t";
}

// Search in title and description
$searchQuery .= " WHERE (t.title LIKE ? OR t.description LIKE ?)";
$searchQuery .= " ORDER BY CASE WHEN t.status = 'done' THEN 1 ELSE 0 END, t.duedate IS NULL, t.duedate ASC LIMIT 8";

$stmt = $conn->prepare($searchQuery);
$searchTerm = "%" . $query . "%";
$stmt->bind_param('ss', $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    // Format the date if it exists
    $dueDate = !empty($row['duedate']) ? date('Y-m-d', strtotime($row['duedate'])) : 'No date';

    $suggestions[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'duedate' => $dueDate,
        'category' => $row['category_name'] ?? '',
        'status' => $row['status'] ?? 'pending'
    ];
}

header('Content-Type: application/json');
echo json_encode($suggestions);