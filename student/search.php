<?php
session_start();
include("db.php");

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$search_query = "";
$results = [];

if(isset($_GET['search'])){
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM books 
            WHERE title LIKE '%$search_query%' OR author LIKE '%$search_query%'";
    $results = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Books</title>
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f5f5f5; display:flex; justify-content:center; padding:50px; }
.container { background:white; padding:30px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); width:600px; }
h2 { color:#4a148c; text-align:center; margin-bottom:20px; }
input[type=text] { width:80%; padding:10px; border-radius:5px; border:1px solid #ccc; }
button { padding:10px 15px; background:#6a1b9a; color:white; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#4a148c; }
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
    <h2>🔍 Search Books</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Enter book title or author" value="<?php echo htmlspecialchars($search_query); ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if(isset($_GET['search'])): ?>
        <h3>Search Results for: "<?php echo htmlspecialchars($search_query); ?>"</h3>

        <?php if($results->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['author']; ?></td>
                            <td><?php echo $row['available'] == 1 ? "Available" : "Borrowed"; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No books found matching your search.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="index.php">← Back to Home</a>
</div>

</body>
</html>