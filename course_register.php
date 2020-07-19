<?php
/* Db connection */
include("db.php");

$id = $_GET['id'];
$query = $conn->query("select * from register where id = '$id' ");
$row = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Course Registration</title>
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
<p>Student course registration form</p>

<div class="form-group">
    <label for="exampleInputSurname">Department</label>
    <input type="text" name="" value="<?php echo $row['department'];?>" readonly class="form-control" value="" id="exampleInputSurname" aria-describedby="surnameHelp">
</div> <br/>

<div class="form-group">
    <label for="exampleFormControlSelect2">Course for year 1</label>
    <select name="one[]" class="form-control" multiple id="exampleFormControlSelect2"  name="sem">
    <?php
        $result = $conn->query("select * from courses where year = '1'");
        while($row = $result->fetch_assoc())
        {

    ?>
        <option value="<?php echo $row['id']?>"><?php echo $row['name']."  Course Unit(".$row['credit_unit'].") ".$row['status']; ?></option>

    <?php
        }
    ?>       
    </select>
</div> <br/>

<div class="form-group">
    <label for="exampleFormControlSelect2">Course year 2</label>
    <select name="two[]" class="form-control" multiple id="exampleFormControlSelect2"  name="sem">
    <?php
        $result = $conn->query("select * from courses where year = '2'");
        while($row = $result->fetch_assoc())
        {

    ?>
        <option value="<?php echo $row['id']?>"><?php echo $row['name']."  Course Unit(".$row['credit_unit'].") ".$row['status']; ?></option>

    <?php
        }
    ?>       
    </select>
</div> <br/>

<div class="form-group">
    <label for="exampleFormControlSelect2">Course for year 3</label>
    <select name="three[]" class="form-control" multiple id="exampleFormControlSelect2"  name="sem">
    <?php
        $result = $conn->query("select * from courses where year = '3'");
        while($row = $result->fetch_assoc())
        {

    ?>
        <option value="<?php echo $row['id']?>"><?php echo $row['name']."  Course Unit(".$row['credit_unit'].") ".$row['status']; ?></option>

    <?php
        }
    ?>       
    </select>
</div> <br/>

<div class="form-group">
    <label for="exampleFormControlSelect2">Course for year 4</label>
    <select name="four[]" class="form-control" multiple id="exampleFormControlSelect2"  name="sem">
    <?php
        $result = $conn->query("select * from courses where year = '4'");
        while($row = $result->fetch_assoc())
        {

    ?>
        <option value="<?php echo $row['id']?>"><?php echo $row['name']."  Course Unit(".$row['credit_unit'].") ".$row['status']; ?></option>

    <?php
        }
    ?>       
    </select>
</div> <br/>

<button type="submit" class="btn btn-primary">Submit</button>
</form>

</body
</html>