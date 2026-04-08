<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only LIBRARIAN allowed
if ($_SESSION['role'] != 'librarian') {
    echo "<h3>Access denied. Librarians only.</h3>";
    exit();
}

// 📌 Fetch all borrow records
$query = "SELECT 
            users.username AS username,
            books.title AS title,
            books.author,
            borrow_records1.borrow_date,
            borrow_records1.return_date,
            borrow_records1.status
          FROM borrow_records1
          JOIN users ON borrow_records1.student_id = users.id
          JOIN books ON borrow_records1.book_id = books.id
          ORDER BY borrow_records1.id DESC";

// Execute query
$result = mysqli_query($conn, $query);

// ⚠️ Check query success
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Librarian - Borrow Records</title>

<style>
    
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f')
                no-repeat center/cover;
    margin: 0;
}

/* Container */
.container {
    padding: 40px;
}

/* Title */
h1 {
    text-align: center;
    color: white;
}

/* Table */
table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    background: rgba(255,255,255,0.95);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background: #6a1b9a;
    color: white;
    font-size: 16px;
}

tr:hover {
    background: #f3e5f5;
}

/* Status badges */
.borrowed {
    color: #ff9800;
    font-weight: bold;
}

.returned {
    color: #4caf50;
    font-weight: bold;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin: 20px;
    background: white;
    color: #4a148c;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.back-btn:hover {
    background: #4a148c;
    color: white;
}
</style>
</head>

<body>

<a href="librarian_dashboard.php" class="back-btn">⬅ Back</a>

<div class="container">

<h1>All Borrow Records</h1>

<table>
<tr>
    <th>Student</th>
    <th>Book Title</th>
    <th>Author</th>
    <th>Borrow Date</th>
    <th>Status</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $statusClass = ($row['status'] == 'returned') ? 'returned' : 'borrowed';
        echo "
        <tr>
            <td>".htmlspecialchars($row['username'])."</td>
            <td>".htmlspecialchars($row['title'])."</td>
            <td>".htmlspecialchars($row['author'])."</td>
            <td>".htmlspecialchars($row['borrow_date'])."</td>
            <td class='$statusClass'>".htmlspecialchars($row['status'])."</td>
        </tr>
        ";
    }
} else {
    echo "<tr><td colspan='5'>No records found.</td></tr>";
}
?>

</table>

</div>

</body>
</html>