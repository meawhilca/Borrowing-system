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
"SELECT COUNT(*) as total FROM borrow_records WHERE return_date IS NULL"))['total'];

$returned_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrow_records WHERE return_date IS NOT NULL"))['total'];

// 📖 Recent Records
$records = mysqli_query($conn, 
"SELECT * FROM borrow_records ORDER BY id DESC LIMIT 10");
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
            <td><?php echo $row['student_name']; ?></td>
            <td><?php echo $row['book_title']; ?></td>

            <!-- ✅ STATUS LOGIC -->
            <td>
                <?php 
                if ($row['return_date'] == NULL) {
                    echo "<span style='color:orange;'>Borrowed</span>";
                } else {
                    echo "<span style='color:green;'>Returned</span>";
                }
                ?>
            </td>

            <!-- ✅ DATE -->
            <td>
                <?php 
                echo $row['return_date'] ? $row['return_date'] : $row['borrow_date']; 
                ?>
            </td>

        </tr>
        <?php } ?>

    </table>

</div>

</body>
</html>