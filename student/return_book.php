<?php
session_start();
include("../db.php");

// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// 🔒 Only STUDENT
if ($_SESSION['role'] != 'student') {
    echo "Access denied.";
    exit();
}

$username = $_SESSION['username'];
$message = "";

// 📌 RETURN BOOK PROCESS
if (isset($_GET['return_id'])) {

    $borrow_id = $_GET['return_id'];

    // Get borrowed book
    $check = mysqli_query($conn, "
        SELECT * FROM borrowed_books 
        WHERE id='$borrow_id' AND username='$username'
    ");

    if (mysqli_num_rows($check) > 0) {

        $row = mysqli_fetch_assoc($check);
        $book_title = $row['book_title'];
        $author = $row['author'];
        $borrow_date = $row['borrow_date'];

        // 1. Insert into returned_books (HISTORY TABLE)
        $stmt = $conn->prepare("
            INSERT INTO returned_books (book_title, author, borrow_date, returned_date)
            VALUES (?, ?, ?, NOW())
        ");

        $stmt->bind_param("sss", $book_title, $author, $borrow_date);
        $stmt->execute();

        // 2. Update borrowed_books status (FIXED)
        mysqli_query($conn, "
            UPDATE borrowed_books 
            SET status = 'returned'
            WHERE id = '$borrow_id'
        ");

        // 3. Increase book quantity
        mysqli_query($conn, "
            UPDATE books 
            SET quantity = quantity + 1 
            WHERE title='$book_title'
        ");

        $message = "Book returned successfully!";
        
    } else {
        $message = "Invalid return request!";
    }
}

// 📌 FETCH ONLY BORROWED BOOKS
$query = "SELECT bb.id, bb.book_title, b.author, bb.borrow_date, bb.status
          FROM borrowed_books bb
          JOIN books b ON bb.book_title = b.title
          WHERE bb.username='$username'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Return Book</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: linear-gradient(rgba(74,20,140,0.7), rgba(106,27,154,0.7)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794')
                no-repeat center/cover;
}

.container { padding: 40px; }

h1 { color: white; text-align: center; }

table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background: #6a1b9a;
    color: white;
}

tr:hover {
    background: #f3e5f5;
}

.btn {
    padding: 8px 12px;
    background: #e53935;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn:hover {
    background: #c62828;
}

.back-btn {
    display: inline-block;
    margin: 20px;
    padding: 10px;
    background: white;
    color: #4a148c;
    text-decoration: none;
    border-radius: 8px;
}

.message {
    text-align: center;
    color: white;
    margin-top: 10px;
}

.deadline {
    font-weight: bold;
    color: #d32f2f;
}
</style>

</head>

<body>

<a href="students_dashboard.php" class="back-btn">⬅ Back</a>

<div class="container">

<h1>Return Books</h1>

<?php if($message != "") echo "<div class='message'>$message</div>"; ?>

<table>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Borrow Date</th>
    <th>Deadline</th>
    <th>Action</th>
</tr>

<?php
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $borrow_date = $row['borrow_date'];

        // 📌 DEADLINE = 7 days after borrow date
        $deadline = date('Y-m-d', strtotime($borrow_date . ' +7 days'));

        echo "<tr>
            <td>" . htmlspecialchars($row['book_title']) . "</td>
            <td>" . htmlspecialchars($row['author']) . "</td>
            <td>" . htmlspecialchars($borrow_date) . "</td>
            <td class='deadline'>$deadline</td>
            <td>";

        if ($row['status'] == 'returned') {
            echo "<span style='color:green;font-weight:bold;'>Returned</span>";
        } else {
            echo "<a href='return_book.php?return_id={$row['id']}'>
                    <button class='btn'>Return</button>
                  </a>";
        }

        echo "</td></tr>";
    }
} else {
    echo "<tr><td colspan='5'>No borrowed books.</td></tr>";
}
?>

</table>

</div>

</body>
</html>