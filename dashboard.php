<?php
session_start();
include 'connection.php';

// Check if user is logged in and has a role set in the session
if (isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];
    $user_name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user is not logged in
    header('location: log-in.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/e3ff8bb9fb.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Dashboard</title>
</head>
<body>
    
</body>
</html>