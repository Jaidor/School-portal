<?php
/* Db connection */
include("db.php");

/* Jquery ajax */
$faculty_id = ($_POST['faculty'])? $_POST['faculty'] : "";    /* Faculty  id */
$result = $conn->query("select * from department where faculty_id =".$faculty_id);
$users_arr = array();

while( $row = $result->fetch_assoc() ){
    $userid = $row['id'];
    $name = $row['department'];
    $users_arr[] = array("id" => $userid, "name" => $name);
}

/*Encoding array to json format */
echo json_encode($users_arr);
?>