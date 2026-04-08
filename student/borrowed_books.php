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

$username = $_SESSION['username'];

// 📚 Fetch borrowed books safely
$result = mysqli_query($conn, "SELECT * FROM borrowed_books WHERE username='$username'");
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Borrowed Books</title>
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794') no-repeat center/cover;
    min-height: 100vh;
}
.container { padding: 40px; max-width: 900px; margin: auto; background: rgba(250, 250, 250, 0.95); border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.3);}
h1 { text-align: center; color: #4a148c; margin-bottom: 30px; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 12px; border-bottom: 1px solid #ccc; text-align: left; }
th { background-color: #6a1b9a; color: white; }
tr:hover { background-color: #f1f1f1; }
.back-btn { display: inline-block; margin-bottom: 20px; text-decoration: none; background: #6a1b9a; color: white; padding: 10px 15px; border-radius: 8px; }
.back-btn:hover { background: #4a148c; }
</style>
</head>
<body>

<div class="container">
<a href="students_dashboard.php" class="back-btn">⬅ Back</a>
<h1>My Borrowed Books</h1>

<?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Borrow Date</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['book_title']); ?></td>
                <td><?= htmlspecialchars($row['borrow_date']); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center; color:#4a148c;">You haven't borrowed any books yet.</p>
<?php endif; ?>

</div>
</body>
</html>