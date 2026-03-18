<?php
session_start();
include("dashboard.php");

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Only allow students, librarians, or admin to view fines
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();
$role = $user['role'];

// Fine settings
$borrow_days_limit = 7; // allowed borrow days
$per_day_fine = 5; // fine per day after due date

// Fetch borrowed books
$borrowed_books = $conn->query("
    SELECT br.id AS borrow_id, bk.title, bk.author, br.borrow_date
    FROM borrow br
    JOIN books bk ON br.book_id = bk.id
    " . ($role == 'student' ? "WHERE br.user_id='$user_id'" : "") . "
    ORDER BY br.borrow_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Calculate Fines</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; display:flex; justify-content:center; padding:50px; }
.container { background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:700px; }
h2 { color:#4a148c; text-align:center; margin-bottom:20px; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
table, th, td { border:1px solid #ccc; }
th, td { padding:10px; text-align:left; }
th { background:#6a1b9a; color:white; }
tr:nth-child(even) { background:#f2f2f2; }
a { text-decoration:none; color:#6a1b9a; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="container">
    <h2>📊 Borrowed Books and Fines</h2>

    <?php if($borrowed_books->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Days Overdue</th>
                    <th>Fine (₱)</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $borrowed_books->fetch_assoc()): 
                    $borrow_date = new DateTime($row['borrow_date']);
                    $due_date = clone $borrow_date;
                    $due_date->modify("+$borrow_days_limit days");
                    $today = new DateTime();

                    $days_overdue = max(0, $today->diff($due_date)->days * ($today > $due_date ? 1 : 0));
                    $fine_amount = $days_overdue * $per_day_fine;
                ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['author']; ?></td>
                    <td><?php echo $borrow_date->format('Y-m-d'); ?></td>
                    <td><?php echo $due_date->format('Y-m-d'); ?></td>
                    <td><?php echo $days_overdue; ?></td>
                    <td><?php echo $fine_amount; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No borrowed books found.</p>
    <?php endif; ?>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>