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

$count_users = count_users_briefs($DB);

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

<body style="background-color: #EFF1F6;">

    <section class="row m-2">
        <!-- the sidebar -->
        <div class="col-2 NAV mt-4 ms-md-5 ">
            <div class="row d-flex justify-content-center ">
                <div class="col-12 d-flex justify-content-center mt-3 p-0">
                    <div class="row d-flex justify-content-center">
                        <img src="imgs/SOLICODE_logo1.png" class="logo col-10 col-md-6 col-lg-5 " alt="logo">
                    </div>
                </div>

                <div class="col-12 text-center mt-2 p-0">
                    <h6 class=" logo_title_1">Spacecode</h6>
                    <h6 class="logo_title_2">Computer Science</h6>
                </div>
                <div class="col-12 mt-4 p-0 row d-flex justify-content-center">
                    <div class="div_link_active col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="dashboard.php " class="m-1">
                            <i class="fa-solid fa-table-cells-large nav_icon_active"></i>
                            <span class="ps-2 d-none d-md-inline nav_link_active"> Dashboard </span>
                        </a>
                    </div>
                    <div class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="briefs.php" class="m-1">
                            <i class="fa-solid fa-box-archive nav_icon"></i>
                            <span class="ps-2 d-none d-md-inline nav_link"> Briefs </span>
                        </a>
                    </div>
                    <?php
                    if ($user_role == 'Admin') {
                        echo '  <div class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="trainers.php" class="m-1"> 
                           <i class="fa-solid fa-user-group nav_icon"></i> 
                           <span class="ps-2 d-none d-md-inline nav_link"> Trainers </span> 
                        </a>
                    </div>';
                    }

                    if ($user_role == 'Admin' || $user_role == 'Trainer') {
                        echo '<div class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="learners.php" class="m-1"> 
                          <i class="fa-solid fa-graduation-cap nav_icon"></i> 
                          <span class="ps-2 d-none d-md-inline nav_link"> Learners </span> 
                        </a>
                    </div>
                    <div class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start" >
                        <a href="briefs-statement.php" class="m-1"> 
                          <i class="fa-solid fa-chart-simple nav_icon"></i> 
                          <span class=" ps-2 d-none d-md-inline nav_link"> Briefs Statement </span> 
                        </a>
                    </div>';
                    }
                    ?>
                    <form action="log-in.php" method="post" class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <button type="submit" class="nav_btn m-1" name="log_out">
                            <i class="fa-solid fa-arrow-right-from-bracket nav_icon"></i>
                            <span class="ps-2 d-none d-md-inline nav_link"> Log Out </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- the dashboard -->
        <div class="col-8 mt-4 ms-md-5 me-5">
            <div class="row">

                <div class="col-sm-12 col-md-8 text-center h3-welcom p-3 ">
                    <h3>Welcome to your dashboard, <?php echo $user_role . ' ' . $user_name ?> </h3>
                </div>

                <div class="col-12 row mt-5">
                    <?php
                    if ($user_role == 'Admin') {
                        echo '
                    <div class="col-md-3 row mt-4 me-3">
                        <div class="col-12 add text-center pt-2">
                           <i class="fa-solid fa-xl fa-user-plus mt-4"></i><br>
                           <button type="button" class="btn mt-4" data-bs-toggle="modal" data-bs-target="#addAdmin"> Add admins </button>
                        </div>
                        <div class="col-12 add text-center mt-3 pt-2">
                           <i class="fa-solid fa-xl fa-user-plus mt-4"></i><br>
                           <button type="button" class="btn mt-4" data-bs-toggle="modal" data-bs-target="#addLearner"> Add learners </button>
                        </div>
                   </div>
                   <div class="col-md-3 row mt-4 me-3">
                        <div class="col-12 add text-center pt-2">
                           <i class="fa-solid fa-xl fa-user-plus mt-4"></i><br>
                           <button type="button" class="btn mt-4" data-bs-toggle="modal" data-bs-target="#addTrainer"> Add trainers </button>
                        </div>
                   </div>

                   <div class="col-md-3 col-sm-12 statistics mt-4 ">
                      <div class="m-2">
                        <h5>Statistics</h5>
                        <span>Januart - June 2021</span>
                      </div>

                      <div class="d-flex ms-2 mt-4">
                        <div class="count count_trainers d-flex justify-content-center align-items-center mt-1">
                           <i class="fa-solid fa-user-group"></i>
                        </div>
                        <div class="ms-2">
                          <span>Trainers</span><br>
                          <span> ' . $count_users[0]['trainers'] . '</span>
                        </div>
                    </div>

                   <div class="d-flex m-2">
                      <div  class="count count_learners d-flex justify-content-center align-items-center mt-1">
                        <i class="fa-solid fa-graduation-cap"></i>
                      </div>
                      <div class="ms-2">
                        <span>Learners</span><br>
                        <span>' . $count_users[1]['learners'] . '</span>
                      </div>
                   </div>

                  <div class="d-flex m-2">
                    <div id="s_b" class="count count_briefs d-flex justify-content-center align-items-center mt-1">
                        <i class="fa-solid fa-box-archive"></i>
                    </div>
                    <div class="ms-2">
                        <span>Briefs</span><br>
                        <span>' . $count_users[2]['briefs'] . '</span>
                    </div>
                  </div>
                 </div> ';
                    }
                    ?>
                </div>
            </div>
        </div>

    </section>

</body>

</html>