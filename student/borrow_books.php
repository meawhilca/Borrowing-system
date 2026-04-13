<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only STUDENT allowed
if ($_SESSION['role'] != 'student') {
    echo "<h3>Access denied. Students only.</h3>";
    exit();
}

// 📥 Borrow logic
if (isset($_POST['borrow'])) {
    if (!empty($_POST['title'])) {

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $username = $_SESSION['username'];

        $borrow_date = date("Y-m-d");

        // 📌 ADD DUE DATE (7 days after borrow)
        $due_date = date("Y-m-d", strtotime($borrow_date . ' +7 days'));

        // ✅ Check if book exists
        $check = mysqli_query($conn, "SELECT * FROM books WHERE title='$title'");

        if ($check && mysqli_num_rows($check) > 0) {

            $book = mysqli_fetch_assoc($check);

            // ❌ Prevent duplicate borrow (only active ones)
            $dup = mysqli_query($conn, "
                SELECT * FROM borrowed_books 
                WHERE username='$username' 
                AND book_title='$title'
                AND status='borrowed'
            ");

            if (mysqli_num_rows($dup) > 0) {
                echo "<script>alert('You already borrowed this book!');</script>";
            } 
            else if ($book['quantity'] > 0) {

                // ✅ INSERT WITH DUE DATE
                $stmt = $conn->prepare("
                    INSERT INTO borrowed_books 
                    (username, book_title, borrow_date, due_date, status)
                    VALUES (?, ?, ?, ?, 'borrowed')
                ");

                $stmt->bind_param("ssss", $username, $title, $borrow_date, $due_date);
                $stmt->execute();

                // 📉 reduce book quantity
                mysqli_query($conn, "
                    UPDATE books 
                    SET quantity = quantity - 1 
                    WHERE title='$title'
                ");

                echo "<script>
                        alert('Book borrowed successfully!');
                        window.location='books.php';
                      </script>";
                exit();

            } else {
                echo "<script>alert('Book not available!');</script>";
            }

        } else {
            echo "<script>alert('Book not found!');</script>";
        }
    }
}

// 📚 Fetch books
$result = mysqli_query($conn, "SELECT * FROM books");
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Books</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794') no-repeat center/cover;
    min-height: 100vh;
}

.container { padding: 40px; }

h1 { color: white; text-align: center; margin-bottom: 30px; }

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.card {
    background: rgba(255,255,255,0.9);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.card:hover { transform: translateY(-5px); }

.card h3 { margin: 0 0 10px; color: #4a148c; }

.card p { margin: 5px 0; font-size: 14px; }

.borrow-btn {
    margin-top: 10px;
    padding: 8px 12px;
    background: #6a1b9a;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.borrow-btn:hover { background: #4a148c; }

.not-available {
    margin-top: 10px;
    color: red;
    font-weight: bold;
}

.back-btn {
    display: inline-block;
    margin: 20px;
    text-decoration: none;
    background: white;
    color: #4a148c;
    padding: 10px 15px;
    border-radius: 8px;
}

.back-btn:hover { background: #ddd; }
</style>
</head>

<body>

<a href="students_dashboard.php" class="back-btn">⬅ Back</a>

<div class="container">
<h1>Available Books</h1>

<div class="grid">

<?php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
?>
    <div class="card">
        <h3><?= htmlspecialchars($row['title']); ?></h3>
        <p><strong>Author:</strong> <?= htmlspecialchars($row['author']); ?></p>
        <p><strong>Quantity:</strong> <?= $row['quantity']; ?></p>

        <form method="POST">
            <input type="hidden" name="title" value="<?= htmlspecialchars($row['title'], ENT_QUOTES); ?>">

            <?php if ($row['quantity'] > 0): ?>
                <button type="submit" name="borrow" class="borrow-btn">Borrow</button>
            <?php else: ?>
                <p class="not-available">Not Available</p>
            <?php endif; ?>

        </form>
    </div>
<?php
    }
} else {
    echo "<p style='color:white;'>No books available.</p>";
}
?>

</div>
</div>

</body>
</html>