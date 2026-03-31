<?php
session_start();
include("../db.php");

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user role
$result = $conn->query("SELECT role FROM users WHERE id='$user_id'");