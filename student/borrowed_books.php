<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only STUDENT allowed
if ($_SESSION['role'] != 'student') {
    echo "<h3>Access denied. Students only.</h3>";
    exit();
}

$username = $_SESSION['username'];

// 📚 Fetch borrowed books safely
$result = mysqli_query($conn, "SELECT * FROM borrowed_books WHERE username='$username'");
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Borrowed Books</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794') no-repeat center/cover;
    min-height: 100vh;
}

/* Container */
.container {

    padding: 30px;
    max-width: 1000px;
    margin: auto;
    background: rgba(250, 250, 250, 0.95);
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

h1 {
    text-align: center;
    color: #4a148c;
    margin-bottom: 30px;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    background: #6a1b9a;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    transition: 0.3s;
}
.back-btn:hover {
    background: #4a148c;
}

/* ===== ENHANCED TABLE ===== */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

th {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
    color: white;
    padding: 14px;
    text-align: left;
    font-weight: 600;
    letter-spacing: 0.5px;
}

td {
    padding: 14px;
    transition: 0.2s;
}

/* Alternate rows */
tr:nth-child(even) {
    background-color: #fafafa;
}

/* Hover */
tr:hover {
    background-color: #f3e5f5;
    transform: scale(1.01);
}

/* Rounded corners */
table tr:first-child th:first-child {
    border-top-left-radius: 12px;
}
table tr:first-child th:last-child {
    border-top-right-radius: 12px;
}

/* Status styling */
.status {
    font-weight: bold;
    text-transform: capitalize;
}

/* Dot indicator */
.status::before {
    content: '';
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}

/* Colors */
.pending {
    color: #ff9800;
}
.pending::before {
    background: #ff9800;
}

.approved {
    color: #4caf50;
}
.approved::before {
    background: #4caf50;
}

.returned {
    color: #2196f3;
}
.returned::before {
    background: #2196f3;
}

/* Empty text */
.empty {
    text-align: center;
    color: #4a148c;
}
</style>
</head>

<body>

<div class="container">

<h1>My Borrowed Books</h1>

<?php if (mysqli_num_rows($result) > 0): ?>
<table>
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Borrow Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['book_title']); ?></td>
            <td><?= htmlspecialchars($row['borrow_date']); ?></td>
            <td>
                
                <span class="status <?= htmlspecialchars($row['status']); ?>">
                    <?= ucfirst(htmlspecialchars($row['status'])); ?>
                </span>
            </td>
        </tr>
    <?php endwhile; ?>
    
    </tbody>
    
</table>
<?php else: ?>
    <p class="empty">You haven't borrowed any books yet.</p>
<?php endif; ?>

<a href="students_dashboard.php" class="back-btn">⬅ Back</a>

</div>

</body>

</html>