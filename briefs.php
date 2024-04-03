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
    <title>Briefs</title>
</head>

<body style="background-color: #EFF1F6;">

    <section class="row ms-2">
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
                    <div class="div_link col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="dashboard.php " class="m-1">
                            <i class="fa-solid fa-table-cells-large nav_icon"></i>
                            <span class="ps-2 d-none d-md-inline nav_link"> Dashboard </span>
                        </a>
                    </div>
                    <div class="div_link_active col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="briefs.php" class="m-1">
                            <i class="fa-solid fa-box-archive nav_icon_active"></i>
                            <span class="ps-2 d-none d-md-inline nav_link_active"> Briefs </span>
                        </a>
                    </div>
                    <?php
                    if ($user_role == 'Admin') {
                        echo '  <div class="div_link col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
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
                    if ($user_role == 'Trainer') {
                        echo '<div class="div_link col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start" >
                        <a href="briefs-statement.php" class="m-1"> 
                          <i class="fa-solid fa-chart-simple nav_icon"></i> 
                          <span class="ps-2 d-none d-md-inline nav_link"> Briefs Statement </span> 
                        </a>
                    </div>';
                    }


                    ?>
                    <form action="log-in.php" method="post" class="div_link col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <button type="submit" class="nav_btn m-1" name="log_out">
                            <i class="fa-solid fa-arrow-right-from-bracket nav_icon"></i>
                            <span class="ps-2 d-none d-md-inline nav_link"> Log Out </span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-9 mt-4 ms-3 ms-md-5 ">
            <div class="row ms-3">
                <div class="col-12 row search_div d-flex align-items-center ">
                    <form action="" class="col-10 col-md-11 " method="post">
                        <div class="input-group ms-2 ">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" name="search" class="search_input col-9 col-md-11" placeholder=" Search briefs by title">
                        </div>
                    </form>
                    <div class="d-flex col-1">
                        <i class="fa-regular fa-bell ms-auto "></i>
                    </div>
                </div>

                <div class="col-12 row ">

                    <div class="col-12 row mt-5">

                        <?php
                        if (isset($_POST['search'])) {
                            $title = $_POST['search'];

                            $briefs_by_search = get_briefs_by_search($DB, $title);

                            if (!empty($briefs_by_search)) {

                                foreach ($briefs_by_search as $brief) {

                                    $currentDate = date('Y-m-d');
                                    $date_start = date('Y-m-d', strtotime($brief['date_start']));
                                    $date_end = date('Y-m-d', strtotime($brief['date_end']));
                                    $currentDate = date('Y-m-d', strtotime($currentDate));

                                    if ($date_start <= $currentDate && $date_end >= $currentDate) {
                                        echo '<div class="col-11 col-md-3 row cart_brief m-3 p-3 text-center">
                                                <h4>' . $brief['title'] . '</h4>
                                                 <div class="col-4 ">
                                                    <h6>from: </h6>
                                                    <h6>to: </h6>
                                                </div>
                                                <div class="col-8 ">
                                                  <span>' . $brief['date_start'] . '</span><br>
                                                  <span>' . $brief['date_end'] . '</span>
                                                </div>
                                                <form action="" class="col-6 mt-3" method="post">
                                                  <input type="hidden" name="id_brief" value=" ' . $brief['id_brief'] . ' ">
                                                  <button class="see_details_brief" type="submit" name="brief_details" >see</button>
                                                </form>';
                                        if ($user_role == 'Learner') {
                                            echo '<form action="" class="col-6 mt-3" method="post">
                                                      <input type="hidden" name="id_brief" value=" ' . $brief['id_brief'] . ' ">
                                                      <button class="start_brief" type="submit" name="brief_start" >start</button>
                                                  </form>';
                                        }
                                        if ($user_role == 'Trainer') {
                                            echo '<span class="col-5 mt-3 toDo"> <i class="fa-solid fa-clipboard-list"></i> To Do </span>';
                                        }
                                        echo '</div>';
                                    }


                                    if ($date_start > $currentDate && $user_role == 'Trainer') {
                                        echo '<div class="col-11 col-md-3 row cart_brief m-3 p-3 text-center">
                                        <form action="" method="post" class="col-12 row">
                                        <h4 class="col-10">' . $brief['title'] . '</h4>
                                        <input type="hidden" name="id_brief" value=" ' . $brief['id_brief'] . ' ">
                                        <button type="submit" name="edit_brief"  class="col-2 edit_brief"> <i class="fa-solid fa-pen-to-square mb-4 ms-2"></i> </button>
                                         </form>
                                                 <div class="col-4 ">
                                                    <h6>from: </h6>
                                                    <h6>to: </h6>
                                                </div>
                                                <div class="col-8 ">
                                                  <span>' . $brief['date_start'] . '</span><br>
                                                  <span>' . $brief['date_end'] . '</span>
                                                </div>
                                                <form action="" class="col-11 mt-3" method="post">
                                                  <input type="hidden" name="id_brief" value=" ' . $brief['id_brief'] . ' ">
                                                  <button class="see_details_brief" type="submit" name="brief_details" >see</button>
                                                  <span class="ms-3 mt-3 coming"><i class="fa-solid fa-clock"></i> Coming</span>
                                                </form>
                                                </div>';
                                    }
                                }
                            } else {

                                echo '<div class="col-lg-8 col-md-9 col-sm-10 no_briefs d-flex justify-content-center align-items-center">
                                        <span style=" color: #7A7E86;"> empty </span>
                                     </div>';
                            }
                        } else {
                            $all_briefs = get_briefs($DB);

                            if (!empty($all_briefs)) {

                                foreach ($all_briefs as $brief) {

                                    $currentDate = date('Y-m-d');
                                    $date_start = date('Y-m-d', strtotime($brief['date_start']));
                                    $date_end = date('Y-m-d', strtotime($brief['date_end']));
                                    $currentDate = date('Y-m-d', strtotime($currentDate));

                                    if ($date_start <= $currentDate && $date_end >= $currentDate) {
                                        echo '<div class="col-11 col-md-3 row cart_brief m-3 p-3 text-center">
                                                <h4>' . $brief['title'] . '</h4>
                                                 <div class="col-4 ">
                                                    <h6>from: </h6>
                                                    <h6>to: </h6>
                                                </div>
                                                <div class="col-8 ">
                                                  <span>' . $brief['date_start'] . '</span><br>
                                                  <span>' . $brief['date_end'] . '</span>
                                                </div>
                                                <form action="" class="col-6 mt-3" method="post">
                                                  <input type="hidden" name="id_brief" value="' . $brief['id_brief'] . '">
                                                  <button class="see_details_brief" type="submit" name="brief_details" >see</button>
                                                </form>';
                                        if ($user_role == 'Learner') {
                                            echo '<form action="" class="col-6 mt-3" method="post">
                                                      <input type="hidden" name="id_brief" value="' . $brief['id_brief'] . '">
                                                      <button class="start_brief" type="submit" name="brief_start" >start</button>
                                                  </form>';
                                        }
                                        if ($user_role == 'Trainer' || $user_role == 'Admin') {
                                            echo '<span class="col-5 mt-3 toDo"> <i class="fa-solid fa-clipboard-list"></i> To Do </span>';
                                        }
                                        echo '</div>';
                                    }

                                    if (($date_start > $currentDate) && ($user_role == 'Trainer' || $user_role == 'Admin')) {
                                        echo '<div class="col-lg-3 col-md-4 col-sm-12 row cart_brief m-3 p-3 text-center">
                                                 <form action="" method="post" class="col-12 row">
                                                      <h4 class="col-10">' . $brief['title'] . '</h4>
                                                      <input type="hidden" name="id_brief" value="' . $brief['id_brief'] . '">
                                                      <button type="submit" name="edit_brief"  class="col-2 edit_brief"> <i class="fa-solid fa-pen-to-square mb-4 ms-2"></i> </button>
                                                 </form>
                                                 <div class="col-4 ">
                                                    <h6>from: </h6>
                                                    <h6>to: </h6>
                                                </div>
                                                <div class="col-8 ">
                                                  <span>' . $brief['date_start'] . '</span><br>
                                                  <span>' . $brief['date_end'] . '</span>
                                                </div>
                                                <form action="" class="col-12 mt-3" method="post">
                                                  <input type="hidden" name="id_brief" value="' . $brief['id_brief'] . '">
                                                  <button class="see_details_brief" type="submit" name="brief_details" >see</button>
                                                  <span class="ms-3 mt-3 coming"><i class="fa-solid fa-clock"></i> Coming</span>
                                                </form>
                                                </div>';
                                    }
                                }
                            } else {
                                echo '<div class="col-lg-8 col-md-9 col-sm-10 no_briefs d-flex justify-content-center align-items-center">
                                        <span style=" color: #7A7E86;"> empty </span>
                                     </div>';
                            }
                        }

                        // get the brief's details and show it in a modal
                        if (isset($_POST['brief_details'])) {
                            $msg = 'dts';
                            $id_brief = $_POST['id_brief'];
                            $brief_details = get_brief_details($DB, $id_brief);

                            foreach ($brief_details as $brief_dts) {
                                $brief_titel = $brief_dts['title'];
                            }
                            show_second_modal($msg);
                        }

                        // update the state's brief to 'doing' 
                        if (isset($_POST['brief_start'])) {
                            $msg = 'str';

                            $id_brief = $_POST['id_brief'];
                            $id_learner = $user_id;
                            $get_brief_by_learner = check_briefs_stat($DB, $id_learner, $id_brief);
                            if ($get_brief_by_learner) {

                                $msg_titel = 'Action rejected';
                                $msg_content = '<img src="imgs/error-img.png" alt="Successful addition">
                                                 <span> You already started this brief , check your dashboard !</span>';
                            } else {
                                learner_doing_brief($DB, $id_brief, $id_learner);

                                $msg_titel = 'Action successful';
                                $msg_content = '<img src="imgs/Successful-img.jpg" alt="Successful addition">';
                            }
                            show_second_modal($msg);
                        }

                        // if (isset($_POST['edit_brief'])) {
                        //     $id_brief = $_POST['id_brief'];
                        //     $brief_details = get_brief_details($DB, $id_brief);


                        //     foreach ($brief_details as $brief_dts) {
                        //         $brief_titel = $brief_dts['title'];
                        //         $brief_date_start = $brief_dts['date_start'];
                        //         $brief_date_end = $brief_dts['date_end'];
                        //         $brief_attachment = $brief_dts['attachment'];
                        //     }
                        //     show_edit_modal();
                        // }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Show the modal after clicked at start's button, to inform whether it was successful or not -->
        <div class="modal fade" id="modal_msg_str" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    </div>
                </div>
            </div>
        </div>
<!-- 
        show the modal to edit brief
        <div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header mt-3 ">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit brief </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="dashboard.php" method="post" class="row" enctype="multipart/form-data">
                            <div class="col-6 mt-3">
                                <label for="titel">Titel </label><br>
                                <input type="text" id="titel" class="add_input w-100" name="title" value="<?php // echo $brief_titel ?>" required>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="attached">Attached piece</label><br>
                                <input type="file" id="attached" class="add_input form-control w-100" value="<?php  ?>" name="attached">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="date_start">Date start </label><br>
                                <input type="date" id="date_start" class="add_input form-control" name="date_start" value="<?php // echo $brief_date_start ?>">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="date_end">Date end </label><br>
                                <input type="date" id="date_end" class="add_input form-control " name="date_end" value="<?php // echo $brief_date_end ?>">
                            </div>
                            <div>
                                <span>Skills :</span><br>
                                <?php
                                // $get_skills = get_skills($DB);
                                // foreach ($get_skills as $skill) {
                                //     $checked = '';
                                //     foreach ($brief_details as $brief_dts) {
                                //         if ($brief_dts['id_skill'] == $skill['id_skill']) {
                                //             $checked = 'checked';
                                //             break;
                                //         }
                                //     }
                                //     echo '<input class="form-check-input" type="checkbox" value="' . $skill['id_skill'] . '" id="s' . $skill['id_skill'] . '" name="' . $skill['id_skill'] . '" ' . $checked . '>
                                //         <label class="form-check-label mb-1" for="s' . $skill['id_skill'] . '">' . $skill['titles'] . '</label><br>';
                                // }
                                ?>
                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <input type="hidden" name="id_brief" value="<?php // echo $brief['id_brief'] ?> ">
                                <button type="submit" class="add_btn" name="save_edit"> Save </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="<?php // echo 'modal_msg_' . $msg ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"> <?php // echo $msg_titel; ?> </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center flex-wrap">
                        <?php // echo $msg_content;  ?>
                    </div>
                </div>
            </div>
        </div> -->

    </section>


    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>