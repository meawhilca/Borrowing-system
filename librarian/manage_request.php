<?php
session_start();
include("../db.php");

// 🔒 Only librarian
if ($_SESSION['role'] != 'librarian') {
    echo "Access denied";
    exit();
}

// ✅ HANDLE APPROVE
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);

    $get = mysqli_query($conn, "SELECT * FROM borrowed_books WHERE id=$id");
    $data = mysqli_fetch_assoc($get);

    if ($data) {
        $title = $data['book_title'];

        mysqli_query($conn, "UPDATE borrowed_books SET status='borrowed' WHERE id=$id");

        mysqli_query($conn, "UPDATE books SET quantity = quantity - 1 WHERE title='$title'");
    }

    header("Location: manage_requests.php");
    exit();
}

// ❌ HANDLE REJECT
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);

    mysqli_query($conn, "UPDATE borrowed_books SET status='rejected' WHERE id=$id");

    header("Location: manage_requests.php");
    exit();
}

// 📋 FETCH REQUESTS
$result = mysqli_query($conn, "SELECT * FROM borrowed_books WHERE status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Requests</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #4a148c, #7b1fa2);
    min-height: 100vh;
}

/* Container */
.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 30px;
    background: rgba(255,255,255,0.95);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* Title */
h2 {
    text-align: center;
    color: #4a148c;
    margin-bottom: 25px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
    color: white;
    padding: 14px;
    text-align: left;
}

td {
    padding: 14px;
    border-bottom: 1px solid #ddd;
}

/* Row hover */
tr:hover {
    background: #f3e5f5;
}

/* Buttons */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.3s;
}

.approve {
    background: #4caf50;
    color: white;
}

.approve:hover {
    background: #388e3c;
}

.reject {
    background: #f44336;
    color: white;
}

.reject:hover {
    background: #c62828;
}

/* Action spacing */
.action-btns a {
    text-decoration: none;
    margin-right: 5px;
}

/* Empty message */
.empty {
    text-align: center;
    padding: 20px;
    color: #4a148c;
}
</style>
</head>

<body>

<div class="container">

<h2>📚 Borrow Requests</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
<table>
<tr>
    <th>Student</th>
    <th>Book</th>
    <th>Borrow Date</th>
    <th>Due Date</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= htmlspecialchars($row['username']); ?></td>
    <td><?= htmlspecialchars($row['book_title']); ?></td>
    <td><?= htmlspecialchars($row['borrow_date']); ?></td>
    <td><?= htmlspecialchars($row['due_date']); ?></td>
    <td class="action-btns">
        <a href="?approve=<?= $row['id']; ?>">
            <button class="btn approve">✔ Approve</button>
        </a>
        <a href="?reject=<?= $row['id']; ?>">
            <button class="btn reject">✖ Reject</button>
        </a>
    </td>
</tr>
<?php } ?>

</table>
<?php else: ?>
    <div class="empty">No pending requests 📭</div>
<?php endif; ?>

</div>

</body>
</html>