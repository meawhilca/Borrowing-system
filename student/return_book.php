<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only STUDENT
if ($_SESSION['role'] != 'student') {
    echo "<h3>Access denied.</h3>";
    exit();
}

$username = $_SESSION['username'];
$message = "";

// 📌 RETURN BOOK
if(isset($_GET['return_id'])){

    $borrow_id = $_GET['return_id'];

    // Get book_id first
    $get = mysqli_query($conn, "SELECT * FROM borrow WHERE id='$borrow_id'");
    $row = mysqli_fetch_assoc($get);
    $book_id = $row['book_id'];

    // Update borrow status
    mysqli_query($conn, "UPDATE borrow SET status='returned' WHERE id='$borrow_id'");

    // Increase book quantity
    mysqli_query($conn, "UPDATE books SET quantity = quantity + 1 WHERE id='$book_id'");

    $message = "Book returned successfully!";
}

// 📌 FETCH BORROWED BOOKS
$query = "SELECT borrow.id, books.title, books.author, borrow.borrow_date 
          FROM borrow 
          JOIN books ON borrow.book_id = books.id
          WHERE borrow.username='$username' AND borrow.status='borrowed'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Return Book</title>

<style>

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794')
                no-repeat center/cover;
    margin: 0;
}

/* Container */
.container {
    padding: 40px;
}

/* Title */
h1 {
    color: white;
    text-align: center;
}

/* Table */
table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    background: rgba(255,255,255,0.9);
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

/* Button */
.btn {
    padding: 8px 12px;
    background: #e53935;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn:hover {
    background: #c62828;
}

/* Back */
.back-btn {
    display: inline-block;
    margin: 20px;
    background: white;
    color: #4a148c;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
}

.message {
    text-align: center;
    color: #fff;
    margin-top: 10px;
}

</style>
</head>

<body>

<a href="students_dashboard.php" class="back-btn">⬅ Back</a>

<div class="container">

<h1>Return Books</h1>

<?php if($message != "") echo "<div class='message'>$message</div>"; ?>

<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Borrow Date</th>
    <th>Action</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "
        <tr>
            <td>{$row['title']}</td>
            <td>{$row['author']}</td>
            <td>{$row['borrow_date']}</td>
            <td>
                <a href='return_book.php?return_id={$row['id']}'>
                    <button class='btn'>Return</button>
                </a>
            </td>
        </tr>
        ";
    }
} else {
    echo "<tr><td colspan='4'>No borrowed books.</td></tr>";
}
?>

</table>

</div>

</body>
</html>