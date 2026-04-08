<?php
session_start();
include(__DIR__ . "/../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'librarian') {
    echo "<h3>Access denied. Librarians only.</h3>";
    exit();
}

// 📊 COUNTS
$total_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM books"))['total'];

$total_students = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM users WHERE role='student'"))['total'];

// ✅ FIXED HERE (NO STATUS COLUMN)
$borrowed_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrow_records1 WHERE return_date IS NULL"))['total'];

$returned_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrow_records1 WHERE return_date IS NOT NULL"))['total'];

// 📖 Recent Records
$records = mysqli_query($conn, 
"SELECT * FROM borrow_records1 ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="../Asset/style.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo">
        <h2>📚 Librarian</h2>
        <p>Reports Panel</p>
    </div>

    <div class="user">
        <span>👤</span>
        <p><?php echo $_SESSION['username']; ?></p>
    </div>

    <nav>
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="manage_books.php">📘 Manage Books</a>
        <a href="add_book.php">➕ Add Book</a>
        <a href="borrow_records.php">📖 Borrow Records</a>
        <a href="students.php">🎓 Students</a>
        <a href="reports.php" class="active">📊 Reports</a>
        <a href="../logout.php" class="logout">🚪 Logout</a>
    </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <h1>📊 Library Reports</h1>

<style>/* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    display: flex;
    background-color: #f4f7f9;
    color: #333;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #1e1e2f;
    color: #fff;
    display: flex;
    flex-direction: column;
    padding: 20px;
    position: fixed;
}

.sidebar .logo h2 {
    font-size: 24px;
    margin-bottom: 5px;
}

.sidebar .logo p {
    font-size: 14px;
    color: #bbb;
}

.sidebar .user {
    margin: 30px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar .user span {
    font-size: 24px;
}

.sidebar nav {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.sidebar nav a {
    text-decoration: none;
    color: #ccc;
    padding: 10px 15px;
    border-radius: 5px;
    transition: 0.3s;
}

.sidebar nav a:hover, 
.sidebar nav a.active {
    background-color: #32324d;
    color: #fff;
}

.sidebar nav a.logout {
    margin-top: auto;
    background-color: #ff4b5c;
    color: #fff;
    text-align: center;
}

.sidebar nav a.logout:hover {
    background-color: #e43b4f;
}

/* MAIN CONTENT */
.main-content {
    margin-left: 270px;
    padding: 30px;
    width: calc(100% - 270px);
}

/* HEADER */
.main-content h1 {
    font-size: 28px;
    margin-bottom: 20px;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.card {
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
    text-align: center;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #555;
}

.card p {
    font-size: 24px;
    font-weight: bold;
    color: #1e1e2f;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
}

table th {
    background-color: #1e1e2f;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #f8f9fa;
}

table tr:hover {
    background-color: #e2e6ea;
}

/* BADGES */
.badge {
    padding: 5px 10px;
    border-radius: 12px;
    color: #fff;
    font-size: 12px;
    font-weight: bold;
}

.badge.borrowed {
    background-color: #ff9f1c;
}

.badge.returned {
    background-color: #2ec4b6;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .sidebar {
        width: 60px;
        padding: 15px;
    }

    .sidebar nav a {
        font-size: 12px;
        padding: 8px;
    }

    .main-content {
        margin-left: 70px;
        padding: 20px;
    }

    .cards {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    }
}</style>

  
    <!-- CARDS -->
    <div class="cards">

        <div class="card">
            <h3>Total Books</h3>
            <p><?php echo $total_books; ?></p>
        </div>

        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>

        <div class="card">
            <h3>Borrowed Books</h3>
            <p><?php echo $borrowed_books; ?></p>
        </div>

        <div class="card">
            <h3>Returned Books</h3>
            <p><?php echo $returned_books; ?></p>
        </div>

    </div>

    <!-- TABLE -->
    <h2>Recent Borrow Records</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Book</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($records)) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['users']); ?></td>
        <td><?php echo htmlspecialchars($row['books']); ?></td>
        <td>
            <?php 
               if ($row['return_date'] == NULL) {
                     echo "<span class='badge borrowed'>Borrowed</span>";
               } else {
                     echo "<span class='badge returned'>Returned</span>";
               }
            ?>
        </td>
        <td>
            <?php echo $row['return_date'] ? $row['return_date'] : $row['borrow_date']; ?>
        </td>
    </tr>
    <?php } ?>
</table>

</div>

</body>
</html>