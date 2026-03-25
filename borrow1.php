<?php
session_start();
include("db.php");

// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

// Check if book ID is provided
if(!isset($_GET['id'])){
    echo "<h3>No book selected.</h3>";
    echo "<a href='index.php'>Go Back</a>";
    exit();
}

$book_id = intval($_GET['id']);

// Fetch book information
$book_query = mysqli_query($conn, "SELECT * FROM books WHERE id=$book_id");
if(mysqli_num_rows($book_query) == 0){
    echo "<h3>Book not found.</h3>";
    echo "<a href='index.php'>Go Back</a>";
    exit();
}

$book = mysqli_fetch_assoc($book_query);

// Check if quantity is available
if($book['quantity'] <= 0){
    echo "<h3>Sorry, '{$book['title']}' is currently unavailable.</h3>";
    echo "<a href='index.php'>Go Back</a>";
    exit();
}

// Reduce the quantity by 1
$new_quantity = $book['quantity'] - 1;
$update_query = mysqli_query($conn, "UPDATE books SET quantity=$new_quantity WHERE id=$book_id");

// Insert into borrow_records
$borrow_query = mysqli_query($conn, "INSERT INTO borrow_records (username, book_id, borrow_date) VALUES ('{$_SESSION['username']}', $book_id, NOW())");

if($update_query && $borrow_query){
    echo "<h3>You have successfully borrowed '{$book['title']}'.</h3>";
    
    // ✅ GO BACK BUTTON
    echo "<a href='index.php'>Go Back to Books</a>";
}
?>