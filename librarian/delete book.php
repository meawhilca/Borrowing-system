<?php
session_start();
include("db.php");

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Only allow librarians or admins
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if(!in_array($user['role'], ['librarian', 'admin'])){
    die("❌ You do not have permission to delete books.");
}

// Check if book ID is provided
if(!isset($_GET['id'])){
    die("❌ No book ID specified.");
}

$book_id = $_GET['id'];

// Check if the book exists
$check = $conn->query("SELECT * FROM books WHERE id='$book_id'");
if($check->num_rows == 0){
    die("❌ Book not found.");
}

// Optional: confirm deletion (you can also do this with JavaScript confirmation on the index page)
if(isset($_GET['confirm']) && $_GET['confirm'] === 'yes'){
    // Delete the book
    $delete = $conn->query("DELETE FROM books WHERE id='$book_id'");

    if($delete){
        header("Location: index.php?message=Book+deleted+successfully");
        exit();
    } else {
        die("❌ Failed to delete the book. Please try again.");
    }
} else {
    // Ask for confirmation
    $book = $check->fetch_assoc();
    echo "<h2>Confirm Deletion</h2>";
    echo "<p>Are you sure you want to delete the book: <strong>{$book['title']}</strong> by <strong>{$book['author']}</strong>?</p>";
    echo "<a href='delete_book.php?id={$book_id}&confirm=yes'>✅ Yes, delete it</a> | <a href='index.php'>❌ Cancel</a>";
}
?>