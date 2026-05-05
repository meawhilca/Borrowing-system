<?php
session_start();
include("../db.php");

// Only librarian allowed
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'librarian'){
    header("Location: login.php");
    exit();
}

// Get all students
$result = $conn->query("SELECT id, username, email FROM users WHERE role='student'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students List</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 30px;
    min-height: 100vh;
    background: linear-gradient(135deg, #2d0b4e, #6a1b9a);
    color: white;
}

/* TITLE */
h2 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 25px;
    letter-spacing: 1px;
}

/* BACK BUTTON */
.back {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 18px;
    background: white;
    color: #6a1b9a;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s;
}

.back:hover {
    transform: scale(1.05);
    background: #f3e5f5;
}

/* TABLE CONTAINER EFFECT */
table {
    width: 50%;
    border-collapse: separate;
    border-spacing: 0;
    background: rgba(255,255,255,0.95);
    color: #333;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* HEADER */
th {
    background: linear-gradient(135deg, #6a1b9a, #8e24aa);
    color: white;
    padding: 14px;
    text-align: left;
    font-size: 14px;
    letter-spacing: 0.5px;
}

/* CELLS */
td {
    padding: 14px;
    border-bottom: 1px solid #eee;
}

/* ROW EFFECT */
tr {
    transition: 0.2s ease;
}

tr:hover {
    background: #f3e5f5;
    transform: scale(1.01);
}

/* STRIPED ROWS */
tr:nth-child(even) {
    background: #fafafa;
}

/* EMPTY ROW */
td[colspan] {
    text-align: center;
    font-weight: bold;
    color: #6a1b9a;
    padding: 20px;
}
</style>

</head>
<body>

<a class="back" href="librarian_dashboard.php">← Back</a>

<h2>📚 Students List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['username']."</td>
                <td>".$row['email']."</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No students found</td></tr>";
}
?>

</table>

</body>
</html>