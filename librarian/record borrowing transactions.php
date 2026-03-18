<?php
session_start();
include("index.php");

// Make sure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Check user role: only students can borrow
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if($user['role'] !== 'student'){
    die("❌ Only students can borrow books.");
}

// Handle borrowing a book
$message = "";
if(isset($_POST['borrow_book'])){
    $book_id = $_POST['book_id'];

    // Check if the book is available
    $check = $conn->query("SELECT * FROM books WHERE id='$book_id'");
    $book = $check->fetch_assoc();

    if($book['available'] == 1){
        // Record borrowing transaction
        $conn->query("INSERT INTO borrow (user_id, book_id) VALUES ('$user_id', '$book_id')");
        // Mark the book as borrowed
        $conn->query("UPDATE books SET available=0 WHERE id='$book_id'");
        $message = "✅ You have successfully borrowed '{$book['title']}'!";
    } else {
        $message = "❌ Sorry, '{$book['title']}' is already borrowed.";
    }
}

// Fetch available books
$books = $conn->query("SELECT * FROM books WHERE available=1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Borrow a Book</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; }
.container { background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:400px; text-align:center; }
select, button { padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc; width:100%; }
button { background:#6a1b9a; color:white; border:none; cursor:pointer; transition:0.3s; }
button:hover { background:#4a148c; }
.message { margin:15px 0; font-weight:bold; }
a { display:block; margin-top:20px; text-decoration:none; color:#6a1b9a; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="container">
    <h2>📚 Borrow a Book</h2>

    <?php if($message != ""): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if($books->num_rows > 0): ?>
    <form method="POST">
        <select name="book_id" required>
            <option value="">-- Select a Book --</option>
            <?php while($book = $books->fetch_assoc()): ?>
                <option value="<?php echo $book['id']; ?>">
                    <?php echo $book['title'] . " by " . $book['author']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="borrow_book">Borrow Book</button>
    </form>
    <?php else: ?>
        <p>All books are currently borrowed. Please check back later.</p>
    <?php endif; ?>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>