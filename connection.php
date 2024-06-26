<?php
$dbn = 'mysql:host=localhost;dbname=spacecode';
$user = 'root';
$pass = '';

try {
    $DB = new PDO($dbn, $user, $pass);
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'failed ' . $e->getMessage();
}

function check_users($DB, $email, $password)
{

    $trainer = "SELECT * FROM trainer WHERE email = :email AND password_tr = :password_tr";
    $check_trainer = $DB->prepare($trainer);
    $check_trainer->bindParam(':email', $email);
    $check_trainer->bindParam(':password_tr', $password);
    $check_trainer->execute();

    $row_u = $check_trainer->fetch(PDO::FETCH_ASSOC);

    $learner = "SELECT * FROM learner WHERE email = :email AND password_ln = :password_ln";
    $check_learner = $DB->prepare($learner);
    $check_learner->bindParam(':email', $email);
    $check_learner->bindParam(':password_ln', $password);
    $check_learner->execute();

    $row_l = $check_learner->fetch(PDO::FETCH_ASSOC);

    return [$row_u, $row_l];
}

function count_users_briefs($DB)
{

    $trainers = "SELECT count(*) as trainers FROM trainer ";
    $stat_trainers = $DB->prepare($trainers);
    $stat_trainers->execute();
    $row_t = $stat_trainers->fetch(PDO::FETCH_ASSOC);

    $learners = "SELECT count(*) as learners FROM learner ";
    $stat_learners = $DB->prepare($learners);
    $stat_learners->execute();
    $row_l = $stat_learners->fetch(PDO::FETCH_ASSOC);

    $briefs = "SELECT count(*) as briefs FROM brief ";
    $stat_briefs = $DB->prepare($briefs);
    $stat_briefs->execute();
    $row_b = $stat_briefs->fetch(PDO::FETCH_ASSOC);

    return [$row_t, $row_l, $row_b];
}

function add_trainer($DB, $first_name, $last_name, $email, $password_tr)
{

    $add_trainer = "INSERT INTO trainer (last_name, first_name, email, password_tr)
                     VALUES ( :last_name , :first_name , :email , :password_tr ) ";
    $stat_add_trainer = $DB->prepare($add_trainer);
    $stat_add_trainer->bindParam(':last_name', $last_name);
    $stat_add_trainer->bindParam(':first_name', $first_name);
    $stat_add_trainer->bindParam(':email', $email);
    $stat_add_trainer->bindParam(':password_tr', $password_tr);
    $stat_add_trainer->execute();
}

function add_admin($DB, $first_name, $last_name, $email, $password_tr)
{

    $add_admin = "INSERT INTO trainer (last_name, first_name, email, password_tr)
                     VALUES ( :last_name , :first_name , :email , :password_tr ) ";
    $stat_add_admin = $DB->prepare($add_admin);
    $stat_add_admin->bindParam(':last_name', $last_name);
    $stat_add_admin->bindParam(':first_name', $first_name);
    $stat_add_admin->bindParam(':email', $email);
    $stat_add_admin->bindParam(':password_tr', $password_tr);
    $stat_add_admin->execute();
}

function add_learner($DB, $first_name, $last_name, $groub, $email, $password_tr)
{

    $add_learner = "INSERT INTO learner (last_name , first_name , group_ , email , password_ln)
                     VALUES ( :last_name , :first_name , :groub , :email , :password_ln ) ";
    $stat_add_learner = $DB->prepare($add_learner);
    $stat_add_learner->bindParam(':last_name', $last_name);
    $stat_add_learner->bindParam(':first_name', $first_name);
    $stat_add_learner->bindParam(':groub', $groub);
    $stat_add_learner->bindParam(':email', $email);
    $stat_add_learner->bindParam(':password_ln', $password_tr);
    $stat_add_learner->execute();
}

// get all the 'doing' briefs by the learner
function get_brief_by_learner($DB, $id_learner)
{
    $brief_doing = "SELECT * FROM brief INNER JOIN learner_brief ON brief.id_brief = learner_brief.id_brief
                  INNER JOIN learner ON learner_brief.id_learner = learner.id_learner 
                  WHERE learner.id_learner = :id_learner";
    $stat_brief_doing = $DB->prepare($brief_doing);
    $stat_brief_doing->bindParam(':id_learner', $id_learner);
    $stat_brief_doing->execute();

    $row_b = $stat_brief_doing->fetchAll(PDO::FETCH_ASSOC);

    return $row_b;
}

function show_second_modal($msg)
{
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    let secondModal = document.getElementById("modal_msg_' . $msg . '");';
    echo '    let bsModal = new bootstrap.Modal(secondModal);'; // Initialize Bootstrap modal
    echo '    bsModal.show();'; // Show the second modal
    echo '});';
    echo '</script>';
}


function show_edit_modal()
{
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    let editModal = new bootstrap.Modal(document.getElementById("modal_edit"));';
    echo '    editModal.show();';
    echo '});';
    echo '</script>';
}

function show_confirm_modal()
{
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    let removeModal = new bootstrap.Modal(document.getElementById("modal_remove"));';
    echo '    removeModal.show();';
    echo '});';
    echo '</script>';
}

function get_skills($DB)
{
    $get_skills = "SELECT * FROM skill";
    $stat_get_skills = $DB->prepare($get_skills);
    $stat_get_skills->execute();

    $row_s = $stat_get_skills->fetchAll(PDO::FETCH_ASSOC);

    return $row_s;
}

function add_brief($DB, $titel, $date_start, $date_end, $attachment, $id_trainer)
{
    $add_brief = "INSERT INTO brief (title , date_start , date_end , attachment , id_trainer)
    VALUES ( :title , :date_start , :date_end , :attachment , :id_trainer )";
    $stat_add_brief = $DB->prepare($add_brief);
    $stat_add_brief->bindParam(':title', $titel);
    $stat_add_brief->bindParam(':date_start', $date_start);
    $stat_add_brief->bindParam(':date_end', $date_end);
    $stat_add_brief->bindParam(':attachment', $attachment, PDO::PARAM_LOB);
    $stat_add_brief->bindParam(':id_trainer', $id_trainer);
    $stat_add_brief->execute();

    // Get the last inserted ID
    $brief_id = $DB->lastInsertId();

    return $brief_id;
}

function delete_brief($DB, $id_brief)
{

    $delete_brief = "START TRANSACTION;
    delete from brief_skill where brief_skill.id_brief = :id_brief ;
    delete from brief where brief.id_brief = :id_brief ;
    COMMIT;";

    $stat_delete_brief = $DB->prepare($delete_brief);
    $stat_delete_brief->bindParam(':id_brief', $id_brief);

    if ($stat_delete_brief->execute()) {
        return true; 
    } else {
        return false; 
    }
}

function add_skills_brief($DB, $id_brief, $id_skill)
{
    $add_skills_brief = "INSERT INTO brief_skill ( id_brief , id_skill)
    VALUES (:id_brief , :id_skill ) ";
    $stat_add_s_brief = $DB->prepare($add_skills_brief);
    $stat_add_s_brief->bindParam(':id_brief', $id_brief);
    $stat_add_s_brief->bindParam(':id_skill', $id_skill);

    $stat_add_s_brief->execute();
}

// mark the brief as done and add the url
function update_brief_state($DB, $id_learner, $id_brief, $url)
{
    $update_brief_state = "UPDATE learner_brief SET state = 'done' , url = :url
                            WHERE id_learner = :id_learner and id_brief = :id_brief ";
    $stat_brief = $DB->prepare($update_brief_state);
    $stat_brief->bindParam(':id_learner', $id_learner);
    $stat_brief->bindParam(':id_brief', $id_brief);
    $stat_brief->bindParam(':url', $url);
    $stat_brief->execute();
}

// learners /////

// get the learners by id from database to edit or delete them
function get_learners_by_id($DB, $id_learner)
{
    $get_learner = "SELECT * FROM learner WHERE id_learner = :id_learner";
    $stat_get_learner = $DB->prepare($get_learner);
    $stat_get_learner->bindParam(':id_learner', $id_learner);
    $stat_get_learner->execute();

    $row_l = $stat_get_learner->fetch(PDO::FETCH_ASSOC);

    return $row_l;
}

function get_learners($DB)
{
    $get_learner = "SELECT * FROM learner";
    $stat_get_learner = $DB->prepare($get_learner);
    $stat_get_learner->execute();

    $row_l = $stat_get_learner->fetchAll(PDO::FETCH_ASSOC);

    return $row_l;
}

function get_learners_by_search($DB, $name)
{
    $get_learner = "SELECT * FROM learner WHERE last_name LIKE :name ";
    $stat_get_learner = $DB->prepare($get_learner);
    $name_search = '%' . $name . '%';
    $stat_get_learner->bindParam(':name', $name_search);
    $stat_get_learner->execute();

    $row_l = $stat_get_learner->fetchAll(PDO::FETCH_ASSOC);

    return $row_l;
}

function edit_learner($DB, $last_name, $first_name, $group, $email, $password_ln, $id_learner)
{
    $edit_learner = "UPDATE learner 
    SET last_name = :last_name,
    first_name = :first_name,
    group_ = :group, 
    email = :email, 
    password_ln = :password_ln 
    WHERE id_learner = :id_learner ";
    $stat_edit_learner = $DB->prepare($edit_learner);
    $stat_edit_learner->bindParam(':last_name', $last_name);
    $stat_edit_learner->bindParam(':first_name', $first_name);
    $stat_edit_learner->bindParam(':group', $group);
    $stat_edit_learner->bindParam(':email', $email);
    $stat_edit_learner->bindParam(':password_ln', $password_ln);
    $stat_edit_learner->bindParam(':id_learner', $id_learner);

    $stat_edit_learner->execute();
}

function remove_learner($DB, $id_learner)
{
    $remove_learner = "DELETE FROM learner WHERE id_learner = :id_learner";
    $stat_remove_learner = $DB->prepare($remove_learner);
    $stat_remove_learner->bindParam(':id_learner', $id_learner);

    $stat_remove_learner->execute();
}

// briefs /////

function get_briefs($DB)
{
    $get_briefs = "SELECT * FROM brief";
    $stat_get_briefs = $DB->prepare($get_briefs);
    $stat_get_briefs->execute();

    $row_b = $stat_get_briefs->fetchAll(PDO::FETCH_ASSOC);

    return $row_b;
}

function get_briefs_by_search($DB, $title)
{
    $get_briefs = "SELECT * FROM brief WHERE title LIKE :title";
    $stat_get_briefs = $DB->prepare($get_briefs);
    $search_title = '%' . $title . '%';
    $stat_get_briefs->bindParam(':title', $search_title);
    $stat_get_briefs->execute();

    $row_b = $stat_get_briefs->fetchAll(PDO::FETCH_ASSOC);

    return $row_b;
}

function get_brief_details($DB, $id_brief)
{
    $get_brie = "SELECT * FROM brief 
                 INNER JOIN brief_skill ON brief.id_brief = brief_skill.id_brief
                 INNER JOIN skill ON brief_skill.id_skill = skill.id_skill 
                 WHERE brief.id_brief = :id_brief ";
    $stat_get_brief_dts = $DB->prepare($get_brie);
    $stat_get_brief_dts->bindParam(':id_brief', $id_brief);
    $stat_get_brief_dts->execute();

    $row_d = $stat_get_brief_dts->fetchAll(PDO::FETCH_ASSOC);

    return $row_d;
}

// traine ////

function get_trainers($DB)
{
    $get_trainers = "SELECT * FROM trainer";
    $stat_get_strainers = $DB->prepare($get_trainers);
    $stat_get_strainers->execute();

    $row_t = $stat_get_strainers->fetchAll(PDO::FETCH_ASSOC);

    return $row_t;
}

function get_trainers_by_search($DB, $name)
{
    $get_trainer = "SELECT * FROM trainer WHERE last_name LIKE :name ";
    $stat_get_trainer = $DB->prepare($get_trainer);
    $name_search = '%' . $name . '%';
    $stat_get_trainer->bindParam(':name', $name_search);
    $stat_get_trainer->execute();

    $row_l = $stat_get_trainer->fetchAll(PDO::FETCH_ASSOC);

    return $row_l;
}

// get the trainers by id from database to edit or delete them
function get_trainer_by_id($DB, $id_trainer)
{
    $get_trainer = "SELECT * FROM trainer WHERE id_trainer = :id_trainer";
    $stat_get_strainer = $DB->prepare($get_trainer);
    $stat_get_strainer->bindParam(':id_trainer', $id_trainer);
    $stat_get_strainer->execute();

    $row_t = $stat_get_strainer->fetch(PDO::FETCH_ASSOC);

    return $row_t;
}

function edit_trainer($DB, $last_name, $first_name, $email, $password_tr, $id_trainer)
{
    $edit_trainer = "UPDATE trainer 
    SET last_name = :last_name,
    first_name = :first_name, 
    email = :email, 
    password_tr = :password_tr 
    WHERE id_trainer = :id_trainer ";
    $stat_edit_trainer = $DB->prepare($edit_trainer);
    $stat_edit_trainer->bindParam(':last_name', $last_name);
    $stat_edit_trainer->bindParam(':first_name', $first_name);
    $stat_edit_trainer->bindParam(':email', $email);
    $stat_edit_trainer->bindParam(':password_tr', $password_tr);
    $stat_edit_trainer->bindParam(':id_trainer', $id_trainer);

    $stat_edit_trainer->execute();
}

function remove_trainer($DB, $id_trainer)
{
    $remove_trainer = "DELETE FROM trainer WHERE id_trainer = :id_trainer";
    $stat_remove_trainer = $DB->prepare($remove_trainer);
    $stat_remove_trainer->bindParam(':id_trainer', $id_trainer);

    $stat_remove_trainer->execute();
}

// update the state of the brief to 'doing'
function learner_doing_brief($DB, $id_brief, $id_learner)
{
    $state = 'doing';
    $learner_brief = "INSERT INTO learner_brief ( id_brief , id_learner , state)
    VALUES (:id_brief , :id_learner , :state) ";
    $stat_learner_brief = $DB->prepare($learner_brief);
    $stat_learner_brief->bindParam(':id_brief', $id_brief);
    $stat_learner_brief->bindParam(':id_learner', $id_learner);
    $stat_learner_brief->bindParam(':state', $state);

    $stat_learner_brief->execute();
}

function check_briefs_stat($DB, $id_learner, $id_brief)
{
    $brief = "SELECT * FROM brief INNER JOIN learner_brief ON brief.id_brief = learner_brief.id_brief
                  INNER JOIN learner ON learner_brief.id_learner = learner.id_learner 
                  WHERE learner.id_learner = :id_learner AND brief.id_brief = :id_brief";
    $stat_brief = $DB->prepare($brief);
    $stat_brief->bindParam(':id_learner', $id_learner);
    $stat_brief->bindParam(':id_brief', $id_brief);
    $stat_brief->execute();

    $row_b = $stat_brief->fetch(PDO::FETCH_ASSOC);

    return $row_b;
}

function count_brief_by_state($DB)
{
    $done = 'done';
    $doing = 'doing';
    // count done briefs
    $done_briefs = "SELECT count(*) as c_done FROM brief INNER JOIN learner_brief ON brief.id_brief = learner_brief.id_brief
                  INNER JOIN learner ON learner_brief.id_learner = learner.id_learner 
                  WHERE learner_brief.state = :done";
    $stat_done_briefs = $DB->prepare($done_briefs);
    $stat_done_briefs->bindParam(':done', $done);
    $stat_done_briefs->execute();
    $row_done = $stat_done_briefs->fetch(PDO::FETCH_ASSOC);

    // count doing briefs
    $doing_briefs = "SELECT count(*) as c_doing FROM brief INNER JOIN learner_brief ON brief.id_brief = learner_brief.id_brief
                     INNER JOIN learner ON learner_brief.id_learner = learner.id_learner 
                     WHERE learner_brief.state = :doing";
    $stat_doing_briefs = $DB->prepare($doing_briefs);
    $stat_doing_briefs->bindParam(':doing', $doing);
    $stat_doing_briefs->execute();
    $row_doing = $stat_doing_briefs->fetch(PDO::FETCH_ASSOC);

    // count all briefs 
    $done_briefs = "SELECT count(*) as c_all FROM brief";
    $stat_done_briefs = $DB->prepare($done_briefs);
    $stat_done_briefs->execute();
    $row_all = $stat_done_briefs->fetch(PDO::FETCH_ASSOC);

    return [$row_all, $row_doing, $row_done];
}

function get_brief_by_title($DB, $title)
{
    $brief_by_title = "SELECT * FROM brief INNER JOIN learner_brief ON brief.id_brief = learner_brief.id_brief
                  INNER JOIN learner ON learner_brief.id_learner = learner.id_learner 
                  WHERE brief.title LIKE :title";
    $stat_briefs = $DB->prepare($brief_by_title);
    $search_title = '%' . $title . '%';
    $stat_briefs->bindParam(':title', $search_title);
    $stat_briefs->execute();

    $row_b = $stat_briefs->fetchAll(PDO::FETCH_ASSOC);

    return $row_b;
}

