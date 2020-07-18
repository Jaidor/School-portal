<?php
/* Db connection */
include("db.php");

echo "Am In successfully";
die();

	

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

<form method="post" action="">
<p>Course Registration</p>

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

</body
</html>