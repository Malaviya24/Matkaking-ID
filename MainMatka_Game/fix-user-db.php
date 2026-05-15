<?php
// include DB connection
include 'include/connect.php';

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=users_jan_jun_2025.csv');

// open PHP output stream
$output = fopen('php://output', 'w');

// write the column headers
fputcsv($output, ['Mobile', 'Date Created']);

// query
$sql = "SELECT mobile, date_created 
        FROM users 
        WHERE date_created BETWEEN '2025-01-01' AND '2025-06-30'
          AND total_deposit > 0
        ORDER BY date_created ASC";

$result = mysqli_query($con, $sql);

// write rows
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
}

fclose($output);
exit;
?>
