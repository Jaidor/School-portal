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
            die();
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

            header("location: ./course_register.php?id=".$row['id']);
     

            // echo '<script>window.location="http://localhost/project/course_register.php";</script>';
        }
        else {

            echo "Invalid login details, please try again later";
            die();         
        }
    }    
}

/* End login */
?>




<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<<<<<<< HEAD
    <title>Student Portal</title>
=======
    <title>Student Registration</title>
>>>>>>> 2ccb77a1e78ef27ce1d3b1cfaf190c0a49a964ed
</head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<body>

<<<<<<< HEAD
<form method="post" action="">
=======
<form method="post" action="controller.php">
>>>>>>> 2ccb77a1e78ef27ce1d3b1cfaf190c0a49a964ed


<div class="form-group">
    <label for="example">Student Portal</label>
    <select name="type" onchange="changeReg(this)"  class="form-control" id="example">
      <option>---Select---</option>
      <option value="return">Returning Student</option>
      <option value="new">New Student</option>
      <option value="login">login</option>
    </select>
</div> <br/>


<script type="text/javascript">
function changeReg(type)
{

    if (type.value == 'new') {
        $('.show_new').show();
        $('.show_return').hide();
        $('.show_login').hide();
    }
    if (type.value == 'return') {
        $('.show_return').show();
        $('.show_new').hide();
        $('.show_login').hide();
    }
    if (type.value == 'login') {
        $('.show_login').show();
        $('.show_new').hide();
        $('.show_return').hide();
    }
}

$(document).ready(function(){
$("#sel_fac").change(function(){
    var fal = $(this).val();

    $.ajax({
        url: 'faculty.php',
        type: 'post',
        data: {faculty:fal},
        dataType: 'json',
        success:function(response){

            var len = response.length;

            $("#sel_fac").empty();
            for( var i = 0; i<len; i++){
                var id = response[i]['id'];
                var name = response[i]['name'];
                
<<<<<<< HEAD
                $("#sel_dept").append("<option value='"+name+"'>"+name+"</option>");
=======
                $("#sel_dept").append("<option value='"+id+"'>"+name+"</option>");
>>>>>>> 2ccb77a1e78ef27ce1d3b1cfaf190c0a49a964ed

            }
        }
    });
});

});
</script>



<div class="show_return" style="display:none;">

<p>Student portal registration page</p>

    <div class="form-group">
        <label for="exampleInputMatric">Matriculation No</label>
<<<<<<< HEAD
        <input type="text" name="matric_return" class="form-control" id="exampleInputMatric" aria-describedby="matricHelp" placeholder="Enter matric no">
=======
        <input type="number" name="matric" class="form-control" id="exampleInputMatric" aria-describedby="matricHelp" placeholder="Enter matric no">
>>>>>>> 2ccb77a1e78ef27ce1d3b1cfaf190c0a49a964ed
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputSurname">Surname</label>
        <input type="text" name="surname_return" class="form-control" id="exampleInputSurname" aria-describedby="surnameHelp" placeholder="Enter surname">
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputFristname">Other Names</label>
        <input type="text" name="other_return" class="form-control" id="exampleInputFirstname" aria-describedby="firstnameHelp" placeholder="Enter othernames">
    </div> <br/>


    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" name="email_return" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputEmail1">Mobile Number</label>
        <input type="number" name="phone_return" class="form-control" id="exampleInputPhone" aria-describedby="phoneHelp" placeholder="Enter phone">
    </div> <br/>


    <div class="form-group">
        <label for="exampleFormControlSelect">State Of Origin</label>
        <select name="state_return" class="form-control" id="exampleFormControlSelect">
        <option>Select state</option>
        <?php
            $result = $conn->query("select * from states");
            while($row = $result->fetch_assoc())
            {
        ?>
            <option value="<?php echo $row['id']?>"><?php echo $row['name']?></option>

        <?php
            }
        ?>       
        </select>
    </div> <br/>


    <div class="form-group">
        <label for="exampleInputYear">Academic Year</label>
        <input type="text" name="year_return" class="form-control" id="exampleInputYear" aria-describedby="yearHelp" placeholder="Enter year">
    </div> <br/>

    <div class="form-group">
        <label for="exampleFormControlSelect2">Semester</label>
        <select name="sem_return" class="form-control" id="exampleFormControlSelect2"  name="sem">
        <option value="1">First Semester</option>
        <option value="2">Second Semester</option>
        </select>
    </div> <br/>
    

    <div class="form-group">
        <label for="exampleInputPasswor1">Password</label>
        <input type="password" name="pass_return" class="form-control" id="exampleInputPassword" placeholder="Password">
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputPassword1">Verify Password</label>
        <input type="password" name="confirm_pass_return" class="form-control" id="exampleInputPassword1" placeholder="Verify Password">
    </div> <br/>
    <button type="submit" class="btn btn-primary">Submit</button>

</div>




<div class="show_new" style="display:none;">


  <div class="form-group">
    <label for="exampleInputSurname">Surname</label>
<<<<<<< HEAD
    <input type="text" name="surname" class="form-control" id="exampleInputSurname" aria-describedby="surnameHelp" placeholder="Enter surname">
=======
    <input type="text" name="name" class="form-control" id="exampleInputSurname" aria-describedby="surnameHelp" placeholder="Enter surname">
>>>>>>> 2ccb77a1e78ef27ce1d3b1cfaf190c0a49a964ed
  </div> <br/>

  <div class="form-group">
    <label for="exampleInputFristname">Other Names</label>
    <input type="text" name="other" class="form-control" id="exampleInputFirstname" aria-describedby="firstnameHelp" placeholder="Enter othernames">
  </div> <br/>

  <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputEmail1">Mobile Number</label>
        <input type="number" name="phone" class="form-control" id="exampleInputPhone" aria-describedby="phoneHelp" placeholder="Enter phone">
    </div> <br/>


  <div class="form-group">
        <label for="exampleFormControlSelect">State Of Origin</label>
        <select name="state" class="form-control" id="exampleFormControlSelect">
        <option>Select state</option>
        <?php
            $result = $conn->query("select * from states");
            while($row = $result->fetch_assoc())
            {
        ?>
            <option value="<?php echo $row['id']?>"><?php echo $row['name']?></option>

        <?php
            }
        ?>       
        </select>
    </div> <br/>

    <div class="form-group">
        <label for="exampleFormControlSelect">Gender</label>
        <select name="gen" class="form-control" id="exampleFormControlSelect">
        <option value="M">Male</option>
        <option value="F">Female</option>      
        </select>
    </div> <br/>

    

    <div class="form-group">
        <label for="exampleFormControlSelect">Faculty</label>
        <select name="faculty" class="form-control" id="sel_fac">
        <option>Select faculty</option>
        <?php
            $result = $conn->query("select * from faculties");
            while($row = $result->fetch_assoc())
            {
        ?>
            <option value="<?php echo $row['id']?>"><?php echo $row['name']?></option>

        <?php
            }
        ?>       
        </select>
    </div> <br/>

    <div class="form-group">
        <label for="exampleForm">Department</label>
        <select name="dept" class="form-control" id="sel_dept">
        <option>---Select---</option>
        </select>
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputYear">Academic Year</label>
        <input type="text" name="year" class="form-control" id="exampleInputYear" aria-describedby="yearHelp" placeholder="Enter year">
    </div> <br/>

    <div class="form-group">
        <label for="exampleFormControlSelect2">Semester</label>
        <select name="sem" class="form-control" id="exampleFormControlSelect2"  name="sem">
        <option value="1">First Semester</option>
        <option value="2">Second Semester</option>
        </select>
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputPasswor1">Password</label>
        <input type="password" name="pass" class="form-control" id="exampleInputPassword" placeholder="Password">
    </div> <br/>

    <div class="form-group">
        <label for="exampleInputPassword1">Verify Password</label>
        <input type="password" name="confirm_pass" class="form-control" id="exampleInputPassword1" placeholder="Verify Password">
    </div> <br/>

    <button type="submit" class="btn btn-primary">Submit</button>


</div>
</form>



<<<<<<< HEAD
<div class="show_login" style="display:none;">
    <form method="post" action="">
=======






<div class="show_login" style="display:none;">
    <form method="post" action="controller.php">
>>>>>>> 2ccb77a1e78ef27ce1d3b1cfaf190c0a49a964ed

        <p>Student Login</p>

        <div class="form-group">
            <label for="exampleInputSurname">Matric No</label>
            <input type="number" name="matric_no" class="form-control" id="exampleInputSurname" aria-describedby="surnameHelp" placeholder="Enter matric no">
        </div> <br/>

        <div class="form-group">
            <label for="exampleInputPassword">Password</label>
            <input type="password" name="pass" class="form-control" id="exampleInputPassword" aria-describedby="passwordHelp" placeholder="Enter password">
        </div> <br/>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

</body
</html>