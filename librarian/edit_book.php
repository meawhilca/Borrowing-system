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
    header("Location:manage_books.php");
    exit();
}

// 📌 Fetch book data
$query = "SELECT * FROM books WHERE id='$book_id'";
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location:manage_books.php");
    exit();
}

$message = "";

// 📌 Update book
if (isset($_POST['update'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $quantity = intval($_POST['quantity']);

    if ($title != "" && $author != "" && $quantity >= 0) {

        $update = "UPDATE books 
                   SET title='$title', author='$author', quantity='$quantity'
                   WHERE id='$book_id'";

        if (mysqli_query($conn, $update)) {
            $message = "✅ Book updated successfully!";

            // refresh data
            $result = mysqli_query($conn, $query);
            $book = mysqli_fetch_assoc($result);

        } else {
            $message = "❌ Failed to update book.";
        }

    } else {
        $message = "⚠️ Please fill all fields properly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Book</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.8), rgba(106,27,154,0.8)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794')
                no-repeat center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* Card */
.container {
    background:white;
    padding:30px;
    border-radius:15px;
    width:350px;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* Title */
h2 {
    text-align:center;
    color:#4a148c;
}

/* Inputs */
input {
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #ccc;
}

/* Button */
button {
    width:100%;
    padding:10px;
    background:#6a1b9a;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button:hover {
    background:#4a148c;
}

/* Message */
.message {
    margin-top:10px;
    text-align:center;
    font-weight:bold;
}

/* Back */
.back {
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#6a1b9a;
}
</style>
</head>

<body>

<div class="container">

<h2>Edit Book</h2>

<form method="POST">

<input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
<input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
<input type="number" name="quantity" value="<?php echo $book['quantity']; ?>" required>

<button type="submit" name="update">Update Book</button>

</form>

<?php if($message != "") echo "<div class='message'>$message</div>"; ?>

<a href="manage_books.php" class="back">⬅ Back to Books</a>

</div>

</body>
</html>