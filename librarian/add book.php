<?php
include("db.php");

if(isset($_POST['add'])){
    
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO books(title,author,quantity) 
            VALUES('$title','$author','$quantity')";
    
    mysqli_query($conn,$sql);

    echo "Book Added Successfully";
}
?>

<form method="POST">

Title: <input type="text" name="title"><br><br>
Author: <input type="text" name="author"><br><br>
Quantity: <input type="number" name="quantity"><br><br>

<button name="add">Add Book</button>

</form>