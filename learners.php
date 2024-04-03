<?php
session_start();
include 'connection.php';

// Check if user is logged in and has a role set in the session
if ((isset($_SESSION['user_role'])) && ($_SESSION['user_role']  == 'Admin' ||  $_SESSION['user_role'] == 'Trainer')) {
    $user_role = $_SESSION['user_role'];
    $user_name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to login page if user is not logged in
    header('location: log-in.php');
    exit();
}

// Edit learner information
if (isset($_POST['save_edit'])) {
    $msg = 'save';
    $first_name = $_POST['f_name'];
    $last_name = $_POST['l_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $group = $_POST['class'];
    $id_learner = $_POST['id_learner'];

    if (strpos($email, 'learner')) {
        edit_learner($DB, $last_name, $first_name, $group, $email, $password, $id_learner);

        header('location: learners.php');
        exit();
    } else {
        $msg_titel = 'Editing error';
        $msg_content = '<img src="imgs/error-img.png" alt="Error editing">
        <span> The learner`s email must contain the word: learner </span>';

        show_second_modal($msg);
    }
}

// confirm the delete of learner from database
if (isset($_POST['confirm_remove'])) {
    $id_learner = $_POST['id_learner'];

    remove_learner($DB, $id_learner);
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
    <title>Learners</title>
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
                            <span class="ps-2 ps-2 d-none d-md-inline nav_link"> Dashboard </span>
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
                        echo '<div class="div_link_active col-10 m-1 p-md-1 d-flex justify-content-center justify-content-md-start">
                        <a href="learners.php" class="m-1"> 
                          <i class="fa-solid fa-graduation-cap nav_icon_active"></i> 
                          <span class="ps-2 d-none d-md-inline nav_link_active"> Learners </span> 
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
                    <form action="" class="col-10 col-md-11 " method="post">
                        <div class="input-group ms-2 ">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" name="search" class="search_input col-9 col-md-11" placeholder=" Search briefs by last name">
                        </div>
                    </form>
                    <div class="d-flex col-1">
                        <i class="fa-regular fa-bell ms-auto "></i>
                    </div>
                </div>

                <div class="col-12 row table-responsive tbl mt-5 p-0">
                    <table class="table m-0">
                        <tr>
                            <th>Full name</th>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Group</th>
                            <?php
                            if ($user_role == 'Admin') {
                                echo ' <th>Edit</th>
                                <th>Remove</th>';
                            }
                            if ($user_role == 'Trainer') {
                                // echo ' <th>Status of brief</th>';
                            }
                            ?>
                        </tr>
                        <?php
                        if (isset($_POST['search'])) {
                            $learner_name = $_POST['search'];
                            $learners_by_search = get_learners_by_search($DB, $learner_name);
                            if (!empty($learners_by_search)) {

                                foreach ($learners_by_search as $learner) {
                                    echo '<tr>
                                    <td>' . $learner['first_name'] . ' ' .  $learner['last_name'] . '</td>
                                    <td>' . $learner['id_learner'] . '</td>
                                    <td>' . $learner['email'] . '</td>
                                    <td>' . $learner['group_'] . '</td>';

                                    if ($user_role == 'Admin') {
                                        echo ' <td> 
                                        <form method="post" action="" id="edit_form_' . $learner['id_learner'] . '" >
                                        <input type="hidden" name="edit" value=" ' . $learner['id_learner'] . ' ">
                                        <i class="fa-solid fa-pen-to-square ed_btn" onclick="submit_form_edit(' . $learner['id_learner'] . ')" ></i>
                                        </form>
                                        </td>
                                        
                                        <td> 
                                        <form method="post" action="" id="remove_form_' . $learner['id_learner'] . '" >
                                        <input type="hidden" name="remove" value=" ' . $learner['id_learner'] . ' ">
                                        <i class="fa-solid fa-user-slash re_btn" onclick="submit_form_remove(' . $learner['id_learner'] . ')" ></i>
                                        </form>
                                        </td>';
                                    }

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr>
                                <td colspan="4"> No learners found  </td>
                                </tr>';
                            }
                        } else {
                            $get_learners = get_learners($DB);
                            if (!empty($get_learners)) {
                                foreach ($get_learners as $learner) {
                                    echo '<tr>
                                <td>' . $learner['first_name'] . ' ' .  $learner['last_name'] . '</td>
                                <td>' . $learner['id_learner'] . '</td>
                                <td>' . $learner['email'] . '</td>
                                <td>' . $learner['group_'] . '</td>';

                                    if ($user_role == 'Admin') {
                                        echo ' <td> 
                                    <form method="post" action="" id="edit_form_' . $learner['id_learner'] . '" >
                                    <input type="hidden" name="edit" value=" ' . $learner['id_learner'] . ' ">
                                    <i class="fa-solid fa-pen-to-square ed_btn" onclick="submit_form_edit(' . $learner['id_learner'] . ')" ></i>
                                    </form>
                                    </td>
                                    
                                    <td> 
                                    <form method="post" action="" id="remove_form_' . $learner['id_learner'] . '" >
                                    <input type="hidden" name="remove" value=" ' . $learner['id_learner'] . ' ">
                                    <i class="fa-solid fa-user-slash re_btn" onclick="submit_form_remove(' . $learner['id_learner'] . ')" ></i>
                                    </form>
                                    </td>';
                                    }

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr>
                            <td colspan="4"> No learners yet  </td>
                            </tr>';
                            }
                        }

                        // Get learner information for editing
                        if (isset($_POST['edit'])) {
                            $learner_id = $_POST['edit'];

                            $learner_info = get_learners_by_id($DB, $learner_id);
                            show_edit_modal();
                        }

                        // Get learner information to delete
                        if (isset($_POST['remove'])) {
                            $learner_id = $_POST['remove'];
                            $learner_info = get_learners_by_id($DB, $learner_id);
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
                        <h1 class="modal-title fs-5" id="exampleModalLabel">remove learner: <?php echo $learner_info['first_name'] . ' ' . $learner_info['last_name'] ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center flex-wrap">
                        <form action="" method="post">
                            <input type="hidden" name="id_learner" value="<?php echo $learner_info['id_learner'] ?>">
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

                        <form action="learners.php" method="post" class="row">
                            <div class="col-6 mt-3">
                                <label for="f_name_t">First name</label><br>
                                <input type="text" id="f_name_t" class="add_input" name="f_name" value="<?php echo $learner_info['first_name'] ?>">
                            </div>
                            <div class="col-6 mt-3">
                                <label for="l_name_t">Last name</label><br>
                                <input type="text" id="l_name_t" class="add_input" name="l_name" value="<?php echo $learner_info['last_name'] ?>">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="email_t">Email address </label><br>
                                <input type="email" id="email_t" class="add_input" style="width: 93%;" name="email" value="<?php echo $learner_info['email'] ?>">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="password_t">Password </label><br>
                                <input type="password" id="password_t" class="add_input" name="password" value="<?php echo $learner_info['password_ln'] ?>">
                            </div>
                            <div class="col-6 mt-3 mb-4">
                                <label for="class">Class </label><br>
                                <select name="class" id="class" class="add_input" style="width: 87%;">
                                    <option value=".."></option>
                                    <option value="w-1" <?php if ($learner_info['group_'] == 'w-1') echo 'selected'; ?>>w-1</option>
                                    <option value="w-2" <?php if ($learner_info['group_'] == 'w-2') echo 'selected'; ?>>w-2</option>
                                    <option value="w-3" <?php if ($learner_info['group_'] == 'w-3') echo 'selected'; ?>>w-3</option>
                                    <option value="w-4" <?php if ($learner_info['group_'] == 'w-4') echo 'selected'; ?>>w-4</option>
                                    <option value="m-1" <?php if ($learner_info['group_'] == 'm-1') echo 'selected'; ?>>m-1</option>
                                </select>

                            </div>
                            <div class="modal-footer col-12 d-flex justify-content-start">
                                <input type="hidden" name="id_learner" value=" <?php echo $learner_info['id_learner'] ?> ">
                                <button type="submit" class="add_btn" name="save_edit">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Show the modal after clicked at edit's button, to inform whether it was successful or not  -->
        <div class="modal fade" id="modal_msg_save" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        function submit_form_edit(learnerId) {
            let formId = 'edit_form_' + learnerId;
            document.getElementById(formId).submit();
        }

        function submit_form_remove(learnerId) {
            let formId = 'remove_form_' + learnerId;
            document.getElementById(formId).submit();
        }
    </script>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>