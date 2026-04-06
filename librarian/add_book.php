<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only LIBRARIAN
if ($_SESSION['role'] != 'librarian') {
    echo "<h3>Access denied. Librarians only.</h3>";
    exit();
}

$message = "";

// 📌 Add Book
if(isset($_POST['add'])){

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $quantity = intval($_POST['quantity']);

    if($title != "" && $author != "" && $quantity > 0){

        $sql = "INSERT INTO books(title, author, quantity) 
                VALUES('$title', '$author', '$quantity')";

        if(mysqli_query($conn, $sql)){
            $message = "✅ Book added successfully!";
        } else {
            $message = "❌ Error adding book.";
        }

    } else {
        $message = "⚠️ Please fill all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Book</title>

<style>

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794')
                no-repeat center/cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Card */
.container {
    background: rgba(255,255,255,0.95);
    padding: 40px;
    border-radius: 20px;
    width: 350px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    text-align: center;
}

/* Title */
h2 {
    color: #4a148c;
}

/* Inputs */
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
}

/* Button */
button {
    width: 100%;
    padding: 10px;
    background: #6a1b9a;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #4a148c;
}

/* Message */
.message {
    margin-top: 10px;
    font-weight: bold;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin-top: 15px;
    background: #999;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
}

</style>
</head>

<body>

<div class="container">

<h2>Add New Book</h2>

<form method="POST">

<input type="text" name="title" placeholder="Book Title" required>
<input type="text" name="author" placeholder="Author" required>
<input type="number" name="quantity" placeholder="Quantity" required>

<button type="submit" name="add">Add Book</button>

</form>

<?php if($message != "") echo "<div class='message'>$message</div>"; ?>

<a href="librarian_dashboard.php" class="back-btn">⬅ Back</a>

</div>

</body>
</html>