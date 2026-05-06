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

/* =========================
   📊 COUNTS
========================= */

// Total books
$total_books = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM books"))['total'];

// Total students
$total_students = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM users WHERE role='student'"))['total'];

// Approved = currently borrowed/approved
$approved_books = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='approved'"))['total'];

// Borrowed (active borrowing)
$borrowed_books = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='borrowed'"))['total'];

// Returned
$returned_books = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='returned'"))['total'];

// Pending
$pending_books = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='pending'"))['total'];

// Rejected
$rejected_books = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='rejected'"))['total'];


/* =========================
   📖 BORROW RECORDS
   (FIXED JOIN HERE)
========================= */

$records = mysqli_query($conn, "
SELECT 
    borrowed_books.id,
    users.username,
    books.title,
    borrowed_books.status,
    borrowed_books.borrow_date,
    borrowed_books.return_date
FROM borrowed_books
JOIN users ON borrowed_books.username = users.username
JOIN books ON borrowed_books.book_id = books.id
ORDER BY borrowed_books.id DESC
LIMIT 500
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Reports</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    background: linear-gradient(135deg, #2d0b4e, #6a1b9a);
    min-height: 100vh;
    color: #333;
}

.main-content {
    margin-left: 180px;
    padding: 40px;
}

h1 {
    font-size: 30px;
    margin-bottom: 25px;
    color: white;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.card {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 22px;
    text-align: center;
    color: white;
}

.card p {
    font-size: 28px;
    font-weight: bold;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 16px;
    overflow: hidden;
}

th {
    background: #6a1b9a;
    color: white;
    padding: 14px;
}

td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #f3e5f5;
}

/* BADGES */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.pending { background: orange; }
.approved { background: #4caf50; }
.borrowed { background: #6a1b9a; }
.returned { background: #00bcd4; }
.rejected { background: #f44336; }

/* BUTTON */
.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background: white;
    color: #6a1b9a;
    text-decoration: none;
    border-radius: 8px;
}
</style>

</head>

<body>

<div class="main-content">

<h1>📊 Library Reports</h1>

<!-- CARDS -->
<div class="cards">
    <div class="card"><h3>Total Books</h3><p><?= $total_books ?></p></div>
    <div class="card"><h3>Total Students</h3><p><?= $total_students ?></p></div>
    <div class="card"><h3>Approved</h3><p><?= $approved_books ?></p></div>
    <div class="card"><h3>Borrowed</h3><p><?= $borrowed_books ?></p></div>
    <div class="card"><h3>Returned</h3><p><?= $returned_books ?></p></div>
    <div class="card"><h3>Pending</h3><p><?= $pending_books ?></p></div>
    <div class="card"><h3>Rejected</h3><p><?= $rejected_books ?></p></div>
</div>

<!-- TABLE -->
<h2 style="color:white;">📚 Borrow Records</h2>

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
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['title']) ?></td>

    <td>
        <?php
        $status = $row['status'];

        if ($status == 'pending') {
            echo "<span class='badge pending'>Pending</span>";
        } elseif ($status == 'approved') {
            echo "<span class='badge approved'>Approved</span>";
        } elseif ($status == 'borrowed') {
            echo "<span class='badge borrowed'>Borrowed</span>";
        } elseif ($status == 'returned') {
            echo "<span class='badge returned'>Returned</span>";
        } elseif ($status == 'rejected') {
            echo "<span class='badge rejected'>Rejected</span>";
        }
        ?>
    </td>

    <td>
        <?= ($status == 'returned') ? $row['return_date'] : $row['borrow_date']; ?>
    </td>
</tr>
<?php } ?>

</table>

<a href="librarian_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

</div>

</body>
</html>