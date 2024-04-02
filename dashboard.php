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
        <div class="col-2 NAV mt-4 ms-md-5 me-5">
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
                        echo '<div class="div_link col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="learners.php" class="m-1"> 
                          <i class="fa-solid fa-graduation-cap nav_icon"></i> 
                          <span class="ps-2 d-none d-md-inline nav_link"> Learners </span> 
                        </a>
                    </div>';
                    }
                    if($user_role == 'Trainer'){
                     echo '<div class="div_link col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start" >
                        <a href="briefs-statement.php" class="m-1"> 
                          <i class="fa-solid fa-chart-simple nav_icon"></i> 
                          <span class="ps-2 d-none d-md-inline nav_link"> Briefs Statement </span> 
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
        <div class="col-8 mt-4 ms-md-5 ">
            <div class="row">

                <div class="col-10 col-md-8 text-center h3-welcom p-3 mb-5">
                    <h3>Welcome to your dashboard, <?php echo $user_role . ' ' . $user_name ?> </h3>
                </div>

                <div class="col-12 row">
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

                   <div class="col-11 col-md-3 statistics mt-4 p-3">
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
                    } else if ($user_role == 'Trainer') {

                        echo ' <div class="col-11 col-md-3 row mt-4 ">
                                 <div class="col-12 add text-center mt-3 pt-2">
                                     <i class="fa-solid fa-xl fa-box-archive mt-4"></i><br>
                                     <button type="button" class="btn mt-4" data-bs-toggle="modal" data-bs-target="#addBrief"> Add briefs </button>
                                 </div>
                             </div>';
                    } else if ($user_role == 'Learner') {

                        $id_learner = $user_id;
                        $brief_by_learner = get_brief_by_learner($DB, $id_learner);
                        if (!empty($brief_by_learner)) {

                            foreach ($brief_by_learner as $brief_) {

                                echo '<div class="col-10 col-md-3 row cart_brief m-3 p-3 text-center">
                                               <h4>' . $brief_['title'] . '</h4>
                                               <div class="col-4 ">
                                                  <h6>from: </h6>
                                                  <h6>to: </h6>
                                                </div>
                                               <div class="col-8 ">
                                              <span>' . $brief_['date_start'] . '</span>
                                               <span>' . $brief_['date_end'] . '</span>
                                              </div>
                                               <form action="" class="col-12 mt-3" method="post">
                                               <input type="hidden" name="brief_state" value="' . $brief_['state'] . '">
                                              <input type="hidden" name="id_brief" value="' . $brief_['id_brief'] . '">
                                              <button class="see_details_brief me-3" type="submit" name="brief_details" >see</button>';

                                if ($brief_['state'] == 'done') {
                                    echo '<span> <i class="fa-solid fa-circle-check"></i> ' . $brief_['state'] . ' </span>';
                                } else if ($brief_['state'] == 'doing') {
                                    echo '<span> <i class="fa-solid fa-hourglass"></i> ' . $brief_['state'] . ' </span>';
                                }
                                echo  '</form>
                                              </div>';
                            }
                        } else {
                            echo '<div class="col-8 div_incomplete_b mt-4 d-flex justify-content-center align-items-center">
                                 <span style=" color: #7A7E86;"> You haven`t started any brief </span>
                             </div>';
                        }
                    }
                    if (isset($_POST['brief_details'])) {
                        $msg = 'dts';
                        $id_brief = $_POST['id_brief'];
                        $brief_state = $_POST['brief_state'];
                        $brief_details = get_brief_details($DB, $id_brief);

                        foreach ($brief_details as $brief_dts) {
                            $brief_titel = $brief_dts['title'];
                            $id_brief = $brief_dts['id_brief'];
                        }
                        show_second_modal($msg);
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- show the brief's details in a modal -->
        <div class="modal fade" id="modal_msg_dts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 " id="exampleModalLabel"> <?php echo $brief_titel; ?> </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body ">
                        <h4>Targeted skills:</h4>
                        <ul>
                            <?php
                            foreach ($brief_details as $brief_dts) {
                                echo '<li> ' . $brief_dts['titles'] . ' </li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="attachment" class="attachment_btn p-2">attachment <i class="fa-solid fa-file-arrow-down"></i></button>
                        <?php
                        if ($user_role == 'Learner') {
                            echo ' <form action="" method="post">
                            <input type="hidden" name="id_learner" value=" ' . $user_id . ' ">
                            <input type="hidden" name="id_brief" value=" ' . $id_brief . ' ">';

                            if ($brief_state == 'doing') {
                                echo '<button class="mark_it_done p-2" type="submit" name="make_it_done" >Done ? </button>
                            <input type="text" name="url" class="add_input" placeholder=" send the url" required>';
                            }
                            echo '</form>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- update the state of brief to 'done' -->
        <?php
        if (isset($_POST['make_it_done'])) {
            if (isset($_POST['url'])) {
                $msg = '_done';
                $url = $_POST['url'];
                $id_learner = $_POST['id_learner'];
                $id_brief = $_POST['id_brief'];
                update_brief_state($DB, $id_learner, $id_brief, $url);

                $msg_titel = 'Successful update';
                $msg_content = '<img src="imgs/Successful-img.jpg" alt="Successful addition">';
                show_second_modal($msg);
            }
        }
        ?>

        <!-- show the modal to add new admin -->
        <div class="modal fade" id="addAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header mt-3 ">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add new admin </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" class="row">
                            <div class="col-6 mt-3">
                                <label for="f_name_a">First name</label><br>
                                <input type="text" id="f_name_a" class="add_input" name="f_name">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="l_name_a">Last name</label><br>
                                <input type="text" id="l_name_a" class="add_input" name="l_name">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="email_a">Email address </label><br>
                                <input type="email" id="email_a" class="add_input" style="width: 93%;" name="email">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="password_a">Password </label><br>
                                <input type="password" id="password_a" class="add_input" name="password">
                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <button type="submit" class="add_btn" name="add_admin">Add admin</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- show the modal to add new learner -->
        <div class="modal fade" id="addLearner" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header mt-3 ">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add new learner </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" class="row">
                            <div class="col-6 mt-3">
                                <label for="f_name_l">First name</label><br>
                                <input type="text" id="f_name_l" class="add_input" name="f_name">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="l_name_l">Last name</label><br>
                                <input type="text" id="l_name_l" class="add_input" name="l_name">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="email_l">Email address </label><br>
                                <input type="email" id="email_l" class="add_input" style="width: 93%;" name="email">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="password_l">Password </label><br>
                                <input type="password" id="password_l" class="add_input" name="password">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="class">Class </label><br>
                                <select name="class" id="class" class="add_input" style="width: 87%;">
                                    <option value=".."></option>
                                    <option value="w-1">w-1</option>
                                    <option value="w-2">w-2</option>
                                    <option value="w-3">w-3</option>
                                    <option value="w-4">w-4</option>
                                    <option value="m-1">m-1</option>
                                </select>
                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <button type="submit" class="add_btn" name="add_learner">Add learner</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- show the modal to add new trainer -->
        <div class="modal fade" id="addTrainer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header mt-3 ">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add new trainer </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" class="row">
                            <div class="col-6 mt-3">
                                <label for="f_name_t">First name</label><br>
                                <input type="text" id="f_name_t" class="add_input" name="f_name">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="l_name_t">Last name</label><br>
                                <input type="text" id="l_name_t" class="add_input" name="l_name">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="email_t">Email address </label><br>
                                <input type="email" id="email_t" class="add_input" style="width: 93%;" name="email">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="password_t">Password </label><br>
                                <input type="password" id="password_t" class="add_input" name="password">
                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <button type="submit" class="add_btn" name="add_trainer">Add trainer</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- show the modal to add new brief -->
        <div class="modal fade" id="addBrief" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header mt-3 ">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add new brief </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="" method="post" class="row" enctype="multipart/form-data">
                            <div class="col-6 mt-3">
                                <label for="titel">Titel </label><br>
                                <input type="text" id="titel" class="add_input w-100" name="titel" required>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="attached">Attached piece</label><br>
                                <input type="file" id="attached" class="add_input form-control w-100" name="attached">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="date_start">Date start </label><br>
                                <input type="date" id="date_start" class="add_input form-control" name="date_start">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="date_end">Date end </label><br>
                                <input type="date" id="date_end" class="add_input form-control " name="date_end">
                            </div>
                            <div>
                                <span>Skills :</span><br>
                                <input class="form-check-input" type="checkbox" value="1" id="s1" name="1">
                                <label class="form-check-label mb-1" for="s1">
                                    Model an application .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="2" id="s2" name="2">
                                <label class="form-check-label mb-1" for="s2">
                                    Create a static and adaptable web user interface .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="3" id="s3" name="3">
                                <label class="form-check-label mb-1" for="s3">
                                    Create a user interface with a content management <br> or e-commerce solution .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="4" id="s4" name="4">
                                <label class="form-check-label mb-1" for="s4">
                                    Create a database .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="5" id="s5" name="5">
                                <label class="form-check-label mb-1" for="s5">
                                    Develop a dynamic web user interface .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="6" id="s6" name="6">
                                <label class="form-check-label mb-1" for="s6">
                                    Develop data access components .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="7" id="s7" name="7">
                                <label class="form-check-label mb-1" for="s7">
                                    Develop the back-end part of a web or mobile web <br> application .
                                </label><br>
                                <input class="form-check-input" type="checkbox" value="8" id="s8" name="8">
                                <label class="form-check-label mb-1" for="s8">
                                    Develop and implement components in a content <br> management or e-commerce application .
                                </label>

                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <button type="submit" class="add_btn" name="add_brief">Add brief</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>



        <?php

        // add new admin
        if (isset($_POST['add_admin'])) {
            $msg = 'add_admin';

            $first_name = $_POST['f_name'];
            $last_name = $_POST['l_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];


            if ($first_name != null &&  $last_name != null &&  $email != null && $password != null) {
                if ($user_role == 'Admin') {

                    // check if the email contain the word 'admin' to inssert new admin in database
                    if (strpos($email, 'admin')) {

                        add_admin($DB, $first_name, $last_name, $email, $password);
                        $msg_titel = 'Successful addition';
                        $msg_content = '<img src="imgs/Successful-img.jpg" alt="Successful addition">';

                        show_second_modal($msg);

                        // check if the email not contain the word 'admin' to show the error message
                    } else if (strpos($email, 'admin') == false) {

                        $msg_titel = 'Error adding';
                        $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                    <span> The admin`s email must contain the word: admin </span>';
                        show_second_modal($msg);
                    }

                    // Check if the user role is not admin to show the error message
                } else  if ($user_role != 'Admin') {
                    $msg_titel = 'Error adding';
                    $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                <span> You do not have access to add users</span>';
                    show_second_modal($msg);
                }
            } else {
                $msg_titel = 'Error adding';
                $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                <span>You have to fill out all the entries</span>';
                show_second_modal($msg);
            }
        }


        // add new trainer
        if (isset($_POST['add_trainer'])) {
            $msg = 'add_trainer';
            $first_name = $_POST['f_name'];
            $last_name = $_POST['l_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($first_name != null &&  $last_name != null &&  $email != null && $password != null) {
                if ($user_role == 'Admin') {

                    // check if the email contain the word: trainer to inssert new trainer in database
                    if (strpos($email, 'trainer')) {

                        add_trainer($DB, $first_name, $last_name, $email, $password);
                        $msg_titel = 'Successful addition';
                        $msg_content = '<img src="imgs/Successful-img.jpg" alt="Successful addition">';
                        show_second_modal($msg);


                        // check if the email not contain the word 'trainer' to show the error message
                    } else  if (strpos($email, 'trainer') == false) {

                        $msg_titel = 'Error adding';
                        $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                        <span> The trainer`s email must contain the word: trainer </span>';
                        show_second_modal($msg);
                    }

                    // Check if the user role is not admin to show the error message
                } else  if ($user_role != 'Admin') {
                    $msg_titel = 'Error adding';
                    $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                   <span> You do not have access to add users</span>';
                    show_second_modal($msg);
                }
            } else {
                $msg_titel = 'Error adding';
                $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                <span>You have to fill out all the entries</span>';
                show_second_modal($msg);
            }
        }

        // add new learner
        if (isset($_POST['add_learner'])) {
            $msg = 'add_learner';
            $first_name = $_POST['f_name'];
            $last_name = $_POST['l_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $class = $_POST['class'];

            if ($first_name != null &&  $last_name != null &&  $email != null && $password != null) {

                if ($user_role == 'Admin') {

                    // check if the email contain the word: learner to inssert new learner in database
                    if (strpos($email, 'learner')) {

                        add_learner($DB, $first_name, $last_name, $class, $email, $password);
                        $msg_titel = 'Successful addition';
                        $msg_content = '<img src="imgs/Successful-img.jpg" alt="Successful addition">';
                        show_second_modal($msg);

                        // check if the email not contain the word 'learner' to show the error message
                    } else  if (strpos($email, 'learner') == false) {

                        $msg_titel = 'Error adding';
                        $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                    <span> The learner`s email must contain the word: learner </span>';
                        show_second_modal($msg);
                    }

                    // Check if the user role is not admin to show the error message
                } else  if ($user_role != 'Admin') {
                    $msg_titel = 'Error adding';
                    $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                <span> You do not have access to add users</span>';
                    show_second_modal($msg);
                }
            } else {
                $msg_titel = 'Error adding';
                $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                            <span>You have to fill out all the entries</span>';
                show_second_modal($msg);
            }
        }


        // add new brief
        if (isset($_POST['add_brief'])) {
            $msg = 'add_brief';

            $titel = $_POST['titel'];
            // $attachment = $_POST['attached'];
            $date_start = date('Y-m-d', strtotime($_POST['date_start']));
            $date_end = date('Y-m-d', strtotime($_POST['date_end']));
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));

            
            if($date_end > $date_start && $date_end >= $currentDate ){

            // Check if a file was uploaded
            if (isset($_FILES['attached']) && $_FILES['attached']['error'] === UPLOAD_ERR_OK) {

                // Read the file contents
                $attachment = file_get_contents($_FILES['attached']['tmp_name']);
            } else {
                $attachment = 'Empty';
            }

            if ($user_role == 'Trainer') {

                // add the brief in database and get the id
                $add_brief_get_id = add_brief($DB, $titel, $date_start, $date_end, $attachment, $user_id);

                // get all the skills from database
                $get_skills = get_skills($DB);

                foreach ($get_skills as $skill) {
                    $skill_id = $skill['id_skill'];

                    // check if the user checked at the input skill to insert the skill's id in table brief_skill with the brief's id
                    if (isset($_POST[$skill_id])) {
                        $skill_input = $_POST[$skill_id];
                        add_skills_brief($DB, $add_brief_get_id, $skill_input);
                    }
                }

                $msg_titel = 'Successful addition';
                $msg_content = '<img src="imgs/Successful-img.jpg" alt="Successful addition">';
                show_second_modal($msg);

                // Check if the user role is not admin or trainer to show the error message
            } else {
                $msg_titel = 'Error adding';
                $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                <span> You do not have access to add briefs</span>';

                show_second_modal($msg);
            }
        }else {
            $msg_titel = 'Error adding';
                $msg_content = '<img src="imgs/error-img.png" alt="Error adding">
                                <span class="text-center"> You entered an end date that is less than the current date or less than the start date</span>';

                show_second_modal($msg);
        }
        }

        ?>

        <!-- Show the modal after clicked at add's button, to inform whether it was successful or not  -->
        <div class="modal fade" id="<?php echo 'modal_msg_' . $msg ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"> <?php echo $msg_titel; ?> </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center flex-wrap">
                        <?php echo $msg_content;  ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- </div> -->
    </section>
    
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>