<?php
session_start();
include 'connection.php';

// Check if user is logged in and has a role set in the session
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Admin') {
    $user_role = $_SESSION['user_role'];
    $user_name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user is not logged in
    header('location: log-in.php');
    exit();
}

// Edit trainer information
if (isset($_POST['save_edit'])) {
    $msg = 'edit';
    $first_name = $_POST['f_name'];
    $last_name = $_POST['l_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id_trainer = $_POST['id_trainer'];

    if (strpos($email, 'trainer')) {
        edit_trainer($DB, $last_name, $first_name, $email, $password, $id_trainer);
        header('location: trainers.php');
        exit();
    } else {
        $msg_titel = 'Editing error';
        $msg_content = '<img src="imgs/error-img.png" alt="Error editing">
                <span> The trainer`s email must contain the word: trainer </span>';

        show_second_modal($msg);
    }
}

// confirm the delete of trainer from database
if (isset($_POST['confirm_remove'])) {
    $id_trainer = $_POST['id_trainer'];

    remove_trainer($DB, $id_trainer);
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
    <title>Trainers</title>
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
                    <div class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="briefs.php" class="m-1">
                            <i class="fa-solid fa-box-archive nav_icon"></i>
                            <span class="ps-2 d-none d-md-inline nav_link"> Briefs </span>
                        </a>
                    </div>
                    <?php
                    if ($user_role == 'Admin') {
                        echo '  <div class="div_link_active col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="trainers.php" class="m-1"> 
                           <i class="fa-solid fa-user-group nav_icon_active"></i> 
                           <span class="ps-2 d-none d-md-inline nav_link_active"> Trainers </span> 
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
                    <form action="log-in.php" method="post" class="div_link col-10  m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <button type="submit" class="nav_btn m-1" name="log_out">
                            <i class="fa-solid fa-arrow-right-from-bracket nav_icon"></i>
                            <span class="ps-2 d-none d-md-inline nav_link"> Log Out </span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-9 ms-2 mt-4 ms-md-5 ">
            <div class="row ms-3">
                <div class="col-12 row search_div d-flex align-items-center ">
                    <form action="" class="col-10 " method="post">
                        <div class="input-group ms-3">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" name="search" class="search_input  w-75" placeholder=" Search by last name" required>
                        </div>
                    </form>
                    <i class="fa-regular fa-bell col-1 "></i>
                </div>


                <div class="col-12 row table-responsive tbl mt-5 p-0">
                    <table class="table m-0 ">
                        <tr>
                            <th>Full name</th>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Edit</th>
                            <th>Remove</th>
                        </tr>
                        <?php
                        if (isset($_POST['search'])) {
                            $trainer_name = $_POST['search'];

                            $trainers_by_search = get_trainers_by_search($DB, $trainer_name);

                            if (!empty($trainers_by_search)) {
                                foreach ($trainers_by_search as $trainer) {
                                    echo '<tr>
                                    <td>' . $trainer['first_name'] . ' ' .  $trainer['last_name'] . '</td>
                                    <td>' . $trainer['id_trainer'] . '</td>
                                    <td>' . $trainer['email'] . '</td>
    
                                    <td> 
                                    <form method="post" action="" id="edit_form_' . $trainer['id_trainer'] . '" >
                                    <input type="hidden" name="edit" value=" ' . $trainer['id_trainer'] . ' ">
                                    <i class="fa-solid fa-pen-to-square ed_btn" onclick="submit_form_edit(' . $trainer['id_trainer'] . ')" ></i>
                                    </form>
                                    </td>
    
                                    <td> 
                                    <form method="post" action="" id="remove_form_' . $trainer['id_trainer'] . '" >
                                    <input type="hidden" name="remove" value=" ' . $trainer['id_trainer'] . ' ">
                                    <i class="fa-solid fa-user-slash re_btn" onclick="submit_form_remove(' . $trainer['id_trainer'] . ')" ></i>
                                    </form>
                                    </td>
                                </tr>';
                                }
                            } else {
                                echo '<tr>
                                <td colspan="5"> No trainers found  </td>
                                </tr>';
                            }
                        } else {
                            $get_trainers = get_trainers($DB);
                            if (!empty($get_trainers)) {
                                foreach ($get_trainers as $trainer) {
                                    echo '<tr>
                                <td>' . $trainer['first_name'] . ' ' .  $trainer['last_name'] . '</td>
                                <td>' . $trainer['id_trainer'] . '</td>
                                <td>' . $trainer['email'] . '</td>

                                <td> 
                                <form method="post" action="" id="edit_form_' . $trainer['id_trainer'] . '" >
                                <input type="hidden" name="edit" value=" ' . $trainer['id_trainer'] . ' ">
                                <i class="fa-solid fa-pen-to-square ed_btn" onclick="submit_form_edit(' . $trainer['id_trainer'] . ')" ></i>
                                </form>
                                </td>

                                <td> 
                                <form method="post" action="" id="remove_form_' . $trainer['id_trainer'] . '" >
                                <input type="hidden" name="remove" value=" ' . $trainer['id_trainer'] . ' ">
                                <i class="fa-solid fa-user-slash re_btn" onclick="submit_form_remove(' . $trainer['id_trainer'] . ')" ></i>
                                </form>
                                </td>
                            </tr>';
                                }
                            } else {
                                echo '<tr>
                            <td> No trainers yet  </td>
                            </tr>';
                            }
                        }

                        // Get trainer information for editing
                        if (isset($_POST['edit'])) {
                            $trainer_id = $_POST['edit'];
                            $trainer_info = get_trainer_by_id($DB, $trainer_id);
                            show_edit_modal();
                        }

                        // Get trainer information to delete
                        if (isset($_POST['remove'])) {
                            $trainer_id = $_POST['remove'];
                            $trainer_info = get_trainer_by_id($DB, $trainer_id);
                            show_confirm_modal();
                        }

                        ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- show the modal to confirm removing the trainer -->
        <div class="modal fade" id="modal_remove" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">remove trainer: <?php echo $trainer_info['first_name'] . ' ' . $trainer_info['last_name'] ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center flex-wrap">
                        <form action="" method="post">
                            <input type="hidden" name="id_trainer" value=" <?php echo $trainer_info['id_trainer'] ?> ">
                            <button type="submit" name="confirm_remove" class="cnf_re_btn"> remove </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- show the modal to edit trainer -->
        <div class="modal fade" id="modal_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header mt-3 ">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">edit trainer </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="trainers.php" method="post" class="row">
                            <div class="col-6 mt-3">
                                <label for="f_name_t">First name</label><br>
                                <input type="text" id="f_name_t" class="add_input" name="f_name" value=" <?php echo $trainer_info['first_name'] ?> ">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="l_name_t">Last name</label><br>
                                <input type="text" id="l_name_t" class="add_input" name="l_name" value=" <?php echo $trainer_info['last_name'] ?> ">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="email_t">Email address </label><br>
                                <input type="email" id="email_t" class="add_input" style="width: 93%;" name="email" value=" <?php echo $trainer_info['email'] ?> ">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="password_t">Password </label><br>
                                <input type="password" id="password_t" class="add_input" name="password" value=" <?php echo $trainer_info['password_tr'] ?> ">
                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <input type="hidden" name="id_trainer" value=" <?php echo $trainer_info['id_trainer'] ?> ">
                                <button type="submit" class="add_btn" name="save_edit" onclick="reloadPage()">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Show the modal after clicked at edit's button, to inform whether it was successful or not  -->
        <div class="modal fade" id="modal_msg_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

    </section>

    <script>
        function submit_form_edit(trainerId) {
            let formId = 'edit_form_' + trainerId;
            document.getElementById(formId).submit();
        }

        function submit_form_remove(trainerId) {
            let formId = 'remove_form_' + trainerId;
            document.getElementById(formId).submit();
        }
    </script>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>