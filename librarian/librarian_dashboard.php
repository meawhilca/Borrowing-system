<?php
session_start();
include(__DIR__ . "/../db.php");


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'librarian'){
    header("Location: login.php");
    exit();
}
// 🔐 Check login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 🔒 Only admin/librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'librarian') {
    echo "<h3>Access denied. Librarians only.</h3>";
    exit();
}

// 📊 COUNT DATA

// Total books
$books = mysqli_query($conn, "SELECT COUNT(*) AS total FROM books");
$total_books = mysqli_fetch_assoc($books)['total'];

// Total students
$students = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='student'");
$total_students = mysqli_fetch_assoc($students)['total'];

// Total borrowed
$borrowed = mysqli_query($conn, "SELECT COUNT(*) AS total FROM borrow_records1");
$total_borrowed = mysqli_fetch_assoc($borrowed)['total'];
?>

<?php include("librarian_sidebar.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Librarian Dashboard</title>

    <style>
        .main {
            margin-left: 230px;
            padding: 20px;
            font-family: Arial;
        }

        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-align: center;
            font-size: 20px;
        }

        .books { background: #6a1b9a; }
        .students { background: #2e7d32; }
        .borrowed { background: #c62828; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #6a1b9a;
            color: white;
        }
    </style>
</head>
<body>

<div class="main">

    <h1>📊 Librarian Dashboard</h1>
    <p>Welcome, <strong><?php echo $_SESSION['username']; ?></strong></p>

    <!-- 📊 CARDS -->
    <div class="cards">
        <div class="card books">
            📚 Total Books<br><strong><?php echo $total_books; ?></strong>
        </div>

        <div class="card students">
            🎓 Total Students<br><strong><?php echo $total_students; ?></strong>
        </div>

        <div class="card borrowed">
            📖 Borrowed Books<br><strong><?php echo $total_borrowed; ?></strong>
        </div>
    </div>

    <!-- 📋 RECENT BORROW -->
    <h2>Recent Borrow Activity</h2>

    <table>
        <tr>
            <th>Student</th>
            <th>Book</th>
            <th>Date</th>
        </tr>

        <?php
        $recent = mysqli_query($conn, "
            SELECT users.username, books.title, borrow_records1.borrow_date
            FROM borrow_records1
            JOIN users ON borrow_records1.username = users.username
            JOIN books ON borrow_records1.book_id = books.id
            ORDER BY borrow_records1.borrow_date DESC
            LIMIT 5
        ");

        while($row = mysqli_fetch_assoc($recent)) {
            echo "<tr>
                    <td>{$row['username']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['borrow_date']}</td>
                  </tr>";
        }
        ?>

    </table>

</div>

</body>
</html>