<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only librarian
if ($_SESSION['role'] != 'librarian') {
    die("❌ Access denied.");
}

// 📌 Get book ID safely
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id <= 0) {
    header("Location: manage_books.php");
    exit();
}

// 📌 Check if book exists
$check = mysqli_query($conn, "SELECT * FROM books WHERE id='$book_id'");

if (mysqli_num_rows($check) == 0) {
    header("Location: manage_books.php");
    exit();
}

// 📌 Delete book
$delete = mysqli_query($conn, "DELETE FROM books WHERE id='$book_id'");

if ($delete) {
    header("Location: manage_books.php?msg=deleted");
} else {
    header("Location: manage_books.php?msg=error");
}

exit();
?>