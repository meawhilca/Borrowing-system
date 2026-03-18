<?php
session_start();
include("db.php");

// Fetch all books
$result = mysqli_query($conn,"SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* BODY */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e3f2fd);
            margin: 0;
            padding: 0;
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            background: #4a148c;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .navbar h1 { font-size: 24px; margin: 0; }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }
        .navbar a:hover { text-decoration: underline; }

        /* CONTAINER */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            margin-top: 40px;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #4a148c;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            color: #555;
            font-size: 16px;
        }

        /* BOOK TABLE */
        #bookTable {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        #bookTable th {
            background: #6a1b9a;
            color: white;
            padding: 12px;
            text-align: center;
        }
        #bookTable td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #bookTable tr:hover {
            background: #f1f1f1;
            transition: 0.3s;
        }

        /* BUTTONS */
        .view-btn {
            background: #6a1b9a;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s;
        }
        .view-btn:hover { background: #4a148c; }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 50px;
            color: #555;
            padding: 20px 0;
            border-top: 1px solid #ccc;
        }

        /* RESPONSIVE */
        @media(max-width:768px) {
            .navbar h1 { font-size: 20px; }
            #bookTable th, #bookTable td { padding: 8px; font-size: 14px; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <h1>📚 Library Dashboard</h1>
        <div>
            <a href="dashboard.php">Home</a>
            <a href="logout.php">Logout</a>
            
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="header">
            <h2>Library Books</h2>
            <p>Manage all books in the library</p>
        </div>

        <!-- BOOK TABLE -->
        <table id="bookTable">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>
                    <a class="view-btn" href="borrow1.php?id=<?php echo $row['id']; ?>">Borrow</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Library Borrowing System © 2026</p>
    </div>

</body>
</html>