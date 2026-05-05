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

// 📊 COUNTS (STATUS-BASED)

// Total books
$total_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM books"))['total'];

// Total students
$total_students = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM users WHERE role='student'"))['total'];

// 📚 Borrowed (APPROVED = currently borrowed)
$borrowed_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='approved'"))['total'];

// 📦 Returned books
$returned_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='returned'"))['total'];

// 📦 borrowed books
$borrowed_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='borrowed'"))['total'];

// ⏳ Pending requests
$pending_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='pending'"))['total'];

// ❌ Rejected requests
$rejected_books = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM borrowed_books WHERE status='rejected'"))['total'];


// 📖 RECORDS
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
JOIN books ON borrowed_books.book_title = books.title
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

/* MAIN CONTAINER */
.main-content {
    margin-left: 180px;
    padding: 40px;
}

/* TITLE */
h1 {
    font-size: 30px;
    margin-bottom: 25px;
    color: white;
    letter-spacing: 1px;
}

/* ===== CARDS ===== */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.card {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 16px;

    padding: 22px;
    text-align: center;

    color: white;

    box-shadow: 0 8px 25px rgba(0,0,0,0.25);

    transition: 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.35);
}

.card h3 {
    font-size: 14px;
    opacity: 0.9;
}

.card p {
    font-size: 28px;
    font-weight: bold;
    margin-top: 10px;
}

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

/* HEADER */
th {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
    color: white;
    padding: 14px;
    text-align: center;
    font-size: 14px;
    letter-spacing: 0.5px;
}

/* CELLS */
td {
    padding: 14px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

/* ROW HOVER */
tr:hover {
    background: #f3e5f5;
    transition: 0.2s;
}

/* STRIPES */
tr:nth-child(even) {
    background: #fafafa;
}

/* ===== BADGES ===== */
.badge {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
    color: white;
    text-transform: capitalize;
    letter-spacing: 0.5px;
}

/* COLORS */
.pending {
    background: linear-gradient(135deg, #ff9800, #ffb74d);
}

.approved {
    background: linear-gradient(135deg, #4caf50, #81c784);
}

.borrowed {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
}

.returned {
    background: linear-gradient(135deg, #00bcd4, #26c6da);
}

.rejected {
    background: linear-gradient(135deg, #f44336, #e57373);
}

/* BACK BUTTON */
.back-btn {
    display: inline-block;
    margin-top: 25px;
    padding: 12px 20px;
    background: white;
    color: #6a1b9a;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s;
}

.back-btn:hover {
    background: #f3e5f5;
    transform: scale(1.05);
}

/* TABLE WRAP LOOK */
h2 {
    color: white;
    margin-top: 20px;
}
    </style>
</head>

<body>

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

        <div class="card">
            <h3>Pending Requests</h3>
            <p><?php echo $pending_books; ?></p>
        </div>

        <div class="card">
            <h3>Rejected Requests</h3>
            <p><?php echo $rejected_books; ?></p>
        </div>

    </div>

    <!-- TABLE -->
    <h2>📚 Borrow Records</h2>

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
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>

            <td>
                <?php 
                $status = $row['status'];

                if ($status == 'pending') {
                    echo "<span class='badge pending'>Pending</span>";
                } elseif ($status == 'approved') {
                    echo "<span class='badge pending'>Pending</span>";
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
                <?php 
                echo ($status == 'returned') 
                    ? $row['return_date'] 
                    : $row['borrow_date']; 
                ?>
            </td>
        </tr>
        <?php } ?>

    </table>

    <!-- BACK BUTTON -->
    <a href="librarian_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

</div>

</body>
</html>