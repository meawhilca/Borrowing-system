<?php
session_start();
include("db.php");

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Only allow students to return their own borrowed books
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

if($user['role'] !== 'student'){
    die("❌ Only students can return books.");
}

$message = "";

// Handle return action
if(isset($_POST['return_book'])){
    $borrow_id = $_POST['borrow_id'];

    // Get the borrow record
    $borrow_result = $conn->query("SELECT * FROM borrow WHERE id='$borrow_id' AND user_id='$user_id'");
    if($borrow_result->num_rows == 0){
        $message = "❌ Borrow record not found.";
    } else {
        $borrow = $borrow_result->fetch_assoc();
        $book_id = $borrow['book_id'];

        // Delete the borrow record (or mark returned)
        $conn->query("DELETE FROM borrow WHERE id='$borrow_id'");
        // Mark the book as available
        $conn->query("UPDATE books SET available=1 WHERE id='$book_id'");

        $message = "✅ You have successfully returned the book!";
    }
}

// Fetch all books currently borrowed by the student
$borrowed_books = $conn->query("
    SELECT br.id AS borrow_id, bk.title, bk.author, br.borrow_date
    FROM borrow br
    JOIN books bk ON br.book_id = bk.id
    WHERE br.user_id='$user_id'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Return Borrowed Books</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; }
.container { background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:500px; text-align:center; }
h2 { color:#4a148c; margin-bottom:20px; }
select, button { padding:12px; margin:10px 0; border-radius:5px; border:1px solid #ccc; width:100%; }
button { background:#6a1b9a; color:white; border:none; cursor:pointer; transition:0.3s; }
button:hover { background:#4a148c; }
.message { margin:15px 0; font-weight:bold; }
a { display:block; margin-top:20px; text-decoration:none; color:#6a1b9a; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="container">
    <h2>📚 Return Borrowed Books</h2>

    <?php if($message != ""): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if($borrowed_books->num_rows > 0): ?>
        <form method="POST">
            <select name="borrow_id" required>
                <option value="">-- Select a Book to Return --</option>
                <?php while($row = $borrowed_books->fetch_assoc()): ?>
                    <option value="<?php echo $row['borrow_id']; ?>">
                        <?php echo $row['title'] . " by " . $row['author'] . " (Borrowed on: " . $row['borrow_date'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="return_book">Return Book</button>
        </form>
    <?php else: ?>
        <p>You currently have no borrowed books.</p>
    <?php endif; ?>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>