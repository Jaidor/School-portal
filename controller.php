<?php
/* Db connection */
include("db.php");

 /* Function to anti hack data */
 function cleanArray($data)
 {
     $clean_data=[];
     foreach ($data as $key=> $info) {
         if(is_array($info)) $clean_data[trim($key)] = $info;
         else $clean_data[trim($key)] = $info;
     }
     return $clean_data;
 }

 function isValidEmail($email="",$checkDomain=false){
    if($checkDomain){
        list($userName, $mailDomain) = split("@", $email);
        if (gethostbyname($mailDomain))  {
          /* This is a valid email domain! */
        }
        else {
          return false;
        }
    }
    if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
        return false;
    } else {
        return true;
    }
}


/* Start registration for both new and returning student */
if(isset($_POST['type'])){

    $params = cleanArray($_POST);

    $type = $params['type'];
    $surname = $params['surname'];
    $othernames = $params['other'];
    $email = $params['email'];
    $phone = $params['phone'];
    $state = $params['state'];
    $gender = $params['gen'];
    $faculty = "1";
    $dept = $params['dept'];
    $year = $params['year'];
    $semester = $params['sem'];
    $pass = $params['pass'];
    $confirm_pass = $params['confirm_pass'];


    $student_id = $params['matric_return'];
    $surname_return = $params['surname_return'];
    $other_return = $params['other_return'];
    $email_return = $params['email_return'];
    $phone_return = $params['phone_return'];
    $state_return = $params['state_return'];
    $year_return = $params['year_return'];
    $sem_return = $params['sem_return'];
    $pass_return = $params['pass_return'];
    $confirm_pass_return = $params['confirm_pass_return'];

    if($type == "new"){
        if($surname == '' || $othernames == '' || $email == '' || $state == '' || $gender == '' || $faculty == '' || $dept == '' || $year == '' || $semester  == '' || $pass == '' || $confirm_pass == ''){

            echo "Field is empty, kindly fill.";
            die();

        }else {

            if(!isValidEmail($email)){
               echo "Invalid email address";
               die();
            }

            if($pass != $confirm_pass){

                echo "Password do not match!";
                die();
            }

            if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',$pass)){

               echo "Password must contain at Least one numeric value (0-9), one Uppercase, one Lowercase and one special character ([#?!@$%^&*-]).']";
               die();
            }

            if(strlen($pass) < 8) {
                echo "Password must not be less than 8 characters...";
                die();
            }

            $matric = mt_rand();
            $hash_pass = password_hash($pass, PASSWORD_BCRYPT);

            $sql = "insert into register (matric_no, surname, names, email, phone, state, gender, faculty, department, academic_year, semester, password)
            VALUES ('$matric', '$surname', '$othernames', '$email', '$phone', '$state', '$gender', '$faculty', '$dept', '$year', '$semester', '$hash_pass')";

            if ($conn->query($sql) === TRUE) {
            echo "Registration successfully...Kindly login with this your matric no ".$matric." ";
            echo '<script>window.location="http://localhost/project/login.php";</script>';
            } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            }

        }
    }

    if($type == "return"){

        if($student_id == '' || $surname_return == '' || $other_return == '' || $email_return == '' || $phone_return == '' || $state_return == '' || $pass_return == '' || $sem_return =='' || $year_return == '' || $confirm_pass_return == ''){

            echo "Field is empty, kindly fill.";
            die();

        }else {

            if(!isValidEmail($email_return)){
               echo "Invalid email address";
               die();
            }

            if($pass_return != $confirm_pass_return){

                echo "Password do not match!";
                die();
            }

            if(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',$pass_return)){

               echo "Password must contain at Least one numeric value (0-9), one Uppercase, one Lowercase and one special character ([#?!@$%^&*-]).']";
               die();
            }

            if(strlen($pass_return) < 8) {
                echo "Password must not be less than 8 characters...";
                die();
            }

            $query = $conn->query("select * from register where matric_no = '$student_id' ");
            $row = $query->fetch_assoc();

            if(!$row){
                 echo "Invalid matric no...Kindly enter correct matric no";
                 die();
            }else{

                $hash_pass = password_hash($pass_return, PASSWORD_BCRYPT);

                $update = $conn->query("update register set surname = '$surname_return', names = '$other_return', email = '$email_return', 
                phone = '$phone_return', state = '$state_return', password = '$hash_pass', semester = '$sem_return', academic_year = '$year_return' 
                where id = '{$row['id']}'");

                if($update === true){
                    echo "Registration successfully...Kindly login with this your matric no ".$student_id." ";
                    die();
                    // echo '<script>window.location="http://localhost/project/login.php";</script>';
                }else {
                    echo "Error on registration ";
                    die();
                    // echo '<script>window.location="http://localhost/project/login.php";</script>';
                }

            }



        }

    }
    
}
/* End of registration both new and returning student */




/* Start login */
if(isset($_POST['matric_no'])){

    $loggedIn = false;

    $params = cleanArray($_POST);
    $matric_no = $params['matric_no'];
    $pass = $params['pass'];


    $sessionID = md5(uniqid() . rand(0, 100));

    if(!empty($matric_no) && !empty($pass) && $loggedIn == false){

        $query = $conn->query("select * from register where matric_no = '$matric_no' ");
        $row = $query->fetch_assoc();

        if(password_verify($pass, $row['password']))
        {

            /* Clear old sessions */
            $conn->query("delete from sessions where session_user_id = '{$row['id']}' OR (session_expires > 600 AND session_expires <= '".time()."' ");

            /* Start session */
            $session_logout = 1200;
            $o = $session_logout > 600 ? (time()+$session_logout) : 1;
            $conn->query("insert into sessions(session_token, session_user_id, session_expires, session_start_time)
            VALUES('$sessionID','{$row['id']}','$o',NOW())");

            echo '<script>window.location="http://localhost/project/course_register.php";</script>';
        }
        else {

            echo "Invalid login details, please try again later";
            die();         
        }
    }    
}

/* End login */
?>