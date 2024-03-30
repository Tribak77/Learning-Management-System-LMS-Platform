<?php
session_start();
include 'connection.php';

// make the session empty to log out 
if(isset($_POST['log_out'])){
    unset($_SESSION['user_role']);
    unset($_SESSION['user_id']);
}

// the log in method
if (isset($_POST['log_in'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $users = check_users($DB, $email, $password);

    $authenticated = false;

    // Check if $users is an array before iterating over it
    if (is_array($users)) {
        foreach ($users as $user) {
            // Check if $user is an array before accessing its elements
            if (is_array($user)) {
                if (strpos($email, 'admin') !== false && $password == $user['password_tr']) {
                    $_SESSION['user_role'] = 'Admin';
                    $_SESSION['user_name'] = $user['first_name'];
                    $_SESSION['user_id'] = $user['id_trainer'];
                    $authenticated = true;
                    break;
                } elseif (strpos($email, 'trainer') !== false && $password == $user['password_tr']) {
                    $_SESSION['user_role'] = 'Trainer';
                    $_SESSION['user_name'] = $user['first_name'];
                    $_SESSION['user_id'] = $user['id_trainer'];
                    $authenticated = true;
                    break;
                } elseif (strpos($email, 'learner') !== false && isset($user['password_ln']) && $password == $user['password_ln']) {
                    $_SESSION['user_role'] = 'Learner';
                    $_SESSION['user_name'] = $user['first_name'];
                    $_SESSION['user_id'] = $user['id_learner'];
                    $authenticated = true;
                    break;
                }
            } 
        }
    }

    if ($authenticated) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo '<span>No users found!</span>';
    }
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
    <title>Log in</title>
</head>

<body style="background-color: #EFF1F6;">
    <div class="mt-5">
        <h1 class="text-center " style="color: #4F4F4F;"> Welcome, Log into your account </h1>
    </div>
    <section class="d-flex justify-content-center mt-5" style="height: 100vh;">
        <div class="bg-white log_in  ">
            <div class="text-center mt-5 welcome">
                <span class="">It is our great pleasure to have </span> <br>
                <span> you on board! </span>
            </div><br>

            <form action="" class="text-center" method="post">
                <input type="email" name="email" placeholder=" Enter Email" class="login_input" required><br>
                <input type="password" name="password" placeholder=" Enter Password" class="login_input" required><br>
                <button type="submit" class="login_button" name="log_in">Login</button>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>