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