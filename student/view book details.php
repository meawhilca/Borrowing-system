<?php
session_start();
include("db.php");

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Check if book ID is provided
if(!isset($_GET['id'])){
    die("❌ No book ID specified.");
}

$book_id = $_GET['id'];

// Fetch book details
$book_result = $conn->query("SELECT * FROM books WHERE id='$book_id'");
if($book_result->num_rows == 0){
    die("❌ Book not found.");
}
$book = $book_result->fetch_assoc();

// Fetch borrowing history of this book
$history_result = $conn->query("
    SELECT br.id AS borrow_id, u.username, u.email, br.borrow_date
    FROM borrow br
    JOIN users u ON br.user_id = u.id
    WHERE br.book_id='$book_id'
    ORDER BY br.borrow_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Details</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; display:flex; justify-content:center; padding:50px; }
.container { background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:700px; }
h2 { color:#4a148c; text-align:center; margin-bottom:20px; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#6a1b9a; color:white; }
tr:nth-child(even) { background:#f2f2f2; }
a { text-decoration:none; color:#6a1b9a; }
a:hover { text-decoration:underline; }
.info { margin:10px 0; }
</style>
</head>
<body>

<div class="container">
    <h2>📖 Book Details</h2>

    <div class="info"><strong>Title:</strong> <?php echo $book['title']; ?></div>
    <div class="info"><strong>Author:</strong> <?php echo $book['author']; ?></div>
    <div class="info"><strong>Availability:</strong> <?php echo $book['available'] == 1 ? "Available" : "Borrowed"; ?></div>

    <h3>Borrowing History</h3>
    <?php if($history_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Borrow ID</th>
                    <th>Student Username</th>
                    <th>Student Email</th>
                    <th>Borrow Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $history_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['borrow_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['borrow_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No borrowing history for this book.</p>
    <?php endif; ?>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>