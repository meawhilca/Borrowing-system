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

        // update status
        mysqli_query($conn, "
            UPDATE borrowed_books 
            SET status='borrowed' 
            WHERE id=$id
        ");

        // reduce quantity
        mysqli_query($conn, "
            UPDATE books 
            SET quantity = quantity - 1 
            WHERE title='$title'
        ");
    }

    header("Location: manage_requests.php");
    exit();
}

// ❌ HANDLE REJECT
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);

    mysqli_query($conn, "
        UPDATE borrowed_books 
        SET status='rejected' 
        WHERE id=$id
    ");

    header("Location: manage_requests.php");
    exit();
}

// 📋 FETCH REQUESTS
$result = mysqli_query($conn, "
    SELECT * FROM borrowed_books 
    WHERE status='pending'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Requests</title>
<style>
body { font-family: Arial; padding: 30px; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 10px; border: 1px solid #ccc; }
button { padding: 5px 10px; cursor: pointer; }
.approve { background: green; color: white; }
.reject { background: red; color: white; }
</style>
</head>
<body>

<h2>📚 Borrow Requests</h2>

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
    <td><?= $row['username']; ?></td>
    <td><?= $row['book_title']; ?></td>
    <td><?= $row['borrow_date']; ?></td>
    <td><?= $row['due_date']; ?></td>
    <td>
        <a href="?approve=<?= $row['id']; ?>">
            <button class="approve">Approve</button>
        </a>
        <a href="?reject=<?= $row['id']; ?>">
            <button class="reject">Reject</button>
        </a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>