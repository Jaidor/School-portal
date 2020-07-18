<?php
/* Db connection */
include("db.php");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Student Registration</title>
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

<form method="post" action="controller.php">


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
                
                $("#sel_dept").append("<option value='"+id+"'>"+name+"</option>");

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
        <input type="number" name="matric" class="form-control" id="exampleInputMatric" aria-describedby="matricHelp" placeholder="Enter matric no">
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
    <input type="text" name="name" class="form-control" id="exampleInputSurname" aria-describedby="surnameHelp" placeholder="Enter surname">
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









<div class="show_login" style="display:none;">
    <form method="post" action="controller.php">

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