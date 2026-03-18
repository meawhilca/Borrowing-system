<?php
session_start();
include("db.php");

// Only allow librarian or admin to view reports
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if(!in_array($user['role'], ['librarian','admin'])){
    die("❌ You do not have permission to view reports.");
}

// Fetch all borrowing transactions
$reports = $conn->query("
    SELECT b.id AS borrow_id, u.username, u.email, bk.title, bk.author, b.borrow_date
    FROM borrow b
    JOIN users u ON b.user_id = u.id
    JOIN books bk ON b.book_id = bk.id
    ORDER BY b.borrow_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Borrowing Reports</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; }
.container { max-width:900px; margin:50px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); }
h2 { text-align:center; color:#4a148c; margin-bottom:20px; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#6a1b9a; color:white; }
tr:nth-child(even) { background:#f2f2f2; }
button { padding:10px 20px; margin-top:20px; background:#6a1b9a; color:white; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#4a148c; }
a { display:inline-block; margin-top:20px; text-decoration:none; color:#6a1b9a; }
</style>
<script>
function printReport() {
    window.print();
}
</script>
</head>
<body>

<div class="container">
    <h2>📊 Borrowing Reports</h2>

    <?php if($reports->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student Username</th>
                <th>Student Email</th>
                <th>Book Title</th>
                <th>Author</th>
                <th>Borrow Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $reports->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['borrow_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['borrow_date']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <button onclick="printReport()">🖨️ Print Report</button>
    <?php else: ?>
        <p>No borrowing transactions found.</p>
    <?php endif; ?>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>