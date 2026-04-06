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

// 📌 Delete book (SAFE)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    if ($id > 0) {
        mysqli_query($conn, "DELETE FROM books WHERE id='$id'");
    }

    header("Location: manage_books.php");
    exit();
}

// 📌 Fetch books
$books = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Books</title>

<style>

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(rgba(74,20,140,0.85), rgba(106,27,154,0.85)),
                url('https://images.unsplash.com/photo-1512820790803-83ca734da794')
                no-repeat center/cover;
    margin: 0;
    padding: 20px;
}

/* Container */
.container {
    background: rgba(255,255,255,0.95);
    padding: 25px;
    border-radius: 15px;
    max-width: 1000px;
    margin: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* Title */
h2 {
    text-align: center;
    color: #4a148c;
}

/* Add button */
.add-btn {
    display: inline-block;
    margin-bottom: 15px;
    padding: 10px 15px;
    background: #6a1b9a;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.add-btn:hover {
    background: #4a148c;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    border-bottom: 1px solid #ccc;
    text-align: center;
}

table th {
    background: #6a1b9a;
    color: white;
}

/* Status */
.available {
    color: green;
    font-weight: bold;
}

.not-available {
    color: red;
    font-weight: bold;
}

/* Actions */
.action a {
    margin: 0 5px;
    text-decoration: none;
    padding: 5px 8px;
    border-radius: 5px;
}

.edit {
    background: #2196f3;
    color: white;
}

.delete {
    background: red;
    color: white;
}

/* Back */
.back {
    display: block;
    margin-top: 15px;
    text-align: center;
    text-decoration: none;
    color: #6a1b9a;
}

</style>
</head>

<body>

<div class="container">

<h2>📚 Manage Books</h2>

<a href="add_book.php" class="add-btn">➕ Add New Book</a>

<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
    <th>Quantity</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($books)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo htmlspecialchars($row['author']); ?></td>
    <td><?php echo $row['quantity']; ?></td>

    <td>
        <?php if($row['quantity'] > 0){ ?>
            <span class="available">Available</span>
        <?php } else { ?>
            <span class="not-available">Out of Stock</span>
        <?php } ?>
    </td>

    <td class="action">
        <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
        <a href="delete_book.php?id=<?php echo $row['id']; ?>" 
   class="delete"
   onclick="return confirm('Are you sure you want to delete this book?')">
   Delete
        </a>
    </td>
</tr>
<?php } ?>

</table>

<a href="librarian_dashboard.php" class="back">⬅ Back to Dashboard</a>

</div>

</body>
</html>