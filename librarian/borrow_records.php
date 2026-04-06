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
$query = "SELECT borrow.username, books.title, books.author, borrow.borrow_date, borrow.status
          FROM borrow
          JOIN books ON borrow.book_id = books.id
          ORDER BY borrow.id DESC";

$result = mysqli_query($conn, $query);
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
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background: #6a1b9a;
    color: white;
}

tr:hover {
    background: #f3e5f5;
}

/* Status */
.borrowed {
    color: orange;
    font-weight: bold;
}

.returned {
    color: green;
    font-weight: bold;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin: 20px;
    background: white;
    color: #4a148c;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
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
            <td>{$row['username']}</td>
            <td>{$row['title']}</td>
            <td>{$row['author']}</td>
            <td>{$row['borrow_date']}</td>
            <td class='$statusClass'>{$row['status']}</td>
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