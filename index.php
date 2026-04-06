<?php
session_start();
include("db.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Home</title>

<link rel="stylesheet" href="Asset/style.css" />

<style>
/* FULL SCREEN BACKGROUND */
body, html {
    margin:0;
    padding:0;
    height:100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.background {
    background: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') no-repeat center center/cover;
    height:100%;
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    text-align:center;
    color:white;
    position:relative;
}

/* OVERLAY */
.background::after {
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    z-index:0;
}

/* CONTENT */
.content {
    position:relative;
    z-index:1;
}

.content h1 {
    font-size:48px;
    margin-bottom:20px;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
}

.content p {
    font-size:20px;
    margin-bottom:40px;
    text-shadow: 1px 1px 6px rgba(0,0,0,0.6);
}

/* BUTTONS */
.button {
    display:inline-block;
    padding:15px 30px;
    margin:10px;
    font-size:18px;
    font-weight:bold;
    color:white;
    background:#6a1b9a;
    border:none;
    border-radius:8px;
    text-decoration:none;
    transition:0.3s;
}

.button:hover {
    background:#4a148c;
    transform:translateY(-3px);
}

/* RESPONSIVE */
@media(max-width:768px){
    .content h1 {
        font-size:36px;
    }
    .content p {
        font-size:16px;
    }
    .button {
        font-size:16px;
        padding:12px 25px;
    }
}
</style>

</head>
<body>

<div class="background">
    <div class="content">
        <h1>📚 Welcome to Our Library</h1>
        <p>Access books, borrow easily, and manage your reading.</p>
        <a class="button" href="login.php">Login</a>
        <a class="button" href="register.php">Create Account</a>>
    
    </div>
</div>

</body>
</html>