<?php
session_start();
include("db.php");

// Only allow librarian or admin to edit books
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if(!in_array($user['role'], ['librarian','admin'])){
    die("❌ You do not have permission to edit books.");
}

// Check if book ID is provided
if(!isset($_GET['id'])){
    die("❌ No book ID specified.");
}

$book_id = $_GET['id'];

// Fetch book info
$book_result = $conn->query("SELECT * FROM books WHERE id='$book_id'");
if($book_result->num_rows == 0){
    die("❌ Book not found.");
}
$book = $book_result->fetch_assoc();

$error = "";
$success = "";

// Handle form submission
if(isset($_POST['update_book'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $available = isset($_POST['available']) ? 1 : 0;

    $update = $conn->query("UPDATE books SET title='$title', author='$author', available='$available' WHERE id='$book_id'");

    if($update){
        $success = "✅ Book information updated successfully!";
        // Refresh book data
        $book_result = $conn->query("SELECT * FROM books WHERE id='$book_id'");
        $book = $book_result->fetch_assoc();
    } else {
        $error = "❌ Failed to update book. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Book Information</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; }
.container { background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:400px; }
h2 { color:#4a148c; text-align:center; margin-bottom:20px; }
input[type=text] { width:100%; padding:12px; margin:10px 0; border-radius:5px; border:1px solid #ccc; }
label { font-weight:bold; }
button { padding:12px; background:#6a1b9a; color:white; border:none; border-radius:5px; cursor:pointer; width:100%; }
button:hover { background:#4a148c; }
.error { color:red; margin:10px 0; }
.success { color:green; margin:10px 0; }
a { display:block; margin-top:15px; text-decoration:none; color:#6a1b9a; text-align:center; }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Book Information</h2>

    <?php if($error != "") echo "<div class='error'>$error</div>"; ?>
    <?php if($success != "") echo "<div class='success'>$success</div>"; ?>

    <form method="POST">
        <label>Book Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>

        <label>Author</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>

        <label>
            <input type="checkbox" name="available" <?php if($book['available'] == 1) echo "checked"; ?>> Available
        </label>

        <button type="submit" name="update_book">Update Book</button>
    </form>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>