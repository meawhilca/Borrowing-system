<?php
session_start();
include("../db.php");

// 🔐 Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// ✅ Validate book ID safely
if(!isset($_GET['id'])){
    die("❌ No book ID specified.");
}

$book_id = intval($_GET['id']);

// 📖 Fetch book details
$book_result = $conn->query("SELECT * FROM books WHERE id='$book_id'");

if(!$book_result || $book_result->num_rows == 0){
    die("❌ Book not found.");
}

$book = $book_result->fetch_assoc();

// 📚 Fetch borrowing history (FIXED TABLE NAME)
$history_result = $conn->query("
    SELECT br.id AS borrow_id, u.username, u.email, br.borrow_date, br.return_date
    FROM borrow_records br
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

body {
    font-family: 'Segoe UI', sans-serif;
    background: #eef1f7;
    padding: 40px;
}

/* CONTAINER */
.container {
    background: white;
    padding: 30px;
    border-radius: 12px;
    max-width: 800px;
    margin: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

/* TITLE */
h2 {
    text-align: center;
    color: #4a148c;
}

/* BOOK INFO */
.info {
    margin: 10px 0;
    font-size: 16px;
}

/* STATUS */
.status {
    padding: 5px 10px;
    border-radius: 20px;
    color: white;
    font-size: 12px;
}

.available { background: green; }
.borrowed { background: orange; }

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th {
    background: #6a1b9a;
    color: white;
    padding: 10px;
}

td {
    padding: 10px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f2f2f2;
}

tr:hover {
    background: #eaeaea;
}

/* BUTTON */
.back-btn {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    background: #6a1b9a;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
}

.back-btn:hover {
    background: #4a148c;
}

</style>
</head>

<body>

<div class="container">

<h2>📖 Book Details</h2>

<div class="info"><strong>Title:</strong> <?php echo $book['title']; ?></div>
<div class="info"><strong>Author:</strong> <?php echo $book['author']; ?></div>

<div class="info">
<strong>Status:</strong> 
<?php if($book['available'] == 1): ?>
    <span class="status available">Available</span>
<?php else: ?>
    <span class="status borrowed">Borrowed</span>
<?php endif; ?>
</div>

<h3>Borrowing History</h3>

<?php if($history_result && $history_result->num_rows > 0): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Email</th>
        <th>Borrow Date</th>
        <th>Status</th>
    </tr>

    <?php while($row = $history_result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['borrow_id']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['borrow_date']; ?></td>

        <td>
            <?php 
            if($row['return_date'] == NULL){
                echo "<span class='status borrowed'>Borrowed</span>";
            } else {
                echo "<span class='status available'>Returned</span>";
            }
            ?>
        </td>
    </tr>
    <?php endwhile; ?>

</table>
<?php else: ?>
<p>No borrowing history for this book.</p>
<?php endif; ?>

<a href="index.php" class="back-btn">← Back</a>

</div>

</body>
</html>