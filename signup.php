<?php
$showAlert = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'partials/_dbconnect.php';
    $name = $_POST["name"];
    $URN = $_POST["urn"];
    // $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    // $exists=false;

    // Check whether this username exists
    $existSql = "SELECT * FROM `users` WHERE urn = '$URN'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if($numExistRows > 0){
        // $exists = true;
        $showError = "URN Already Exists";
    }
    else{
        // $exists = false; 
        if(($password == $cpassword)){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` ( `name`,`urn`, `password`, `dt`) VALUES ('$name','$URN', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result){
                $showAlert = true;
            }
        }
        else{
            $showError = "Passwords do not match";
        }
    }
}
    
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>SignUp</title>
  </head>
  <body>
    <?php require 'partials/_nav.php' ?>
    <?php
    if($showAlert){
    echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your account is now created and you can login
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
    }
    if($showError){
    echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '. $showError.'
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
    }
    ?>

    <div class="container my-2">
    <figure class="text-center">
        <blockquote class="blockquote text-danger">
          <p class="h1">Signup Portal</p>
        </blockquote>
        <figcaption class="blockquote-footer">
          GURU NANAK DEV ENGINEERING COLLEGE
        </figcaption>
      </figure>
</div>

    <section class= "h-100 bg-dark ">
<div class="container h-100 py-5">

<div class="card">
  <div class="card-body">
    <h1 class="text-center text-uppercase">Enter your Details</h1>
        <form action="/NDA/signup.php" method="post">
        <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" maxlength = "30" name="name" class="form-control" id="name" aria-describedby="emailHelp" Required>
            </div>
            <div class="form-group mb-3">
                <label for="urn" class="form-label">University Roll Number</label>
                <input type="text" minlength = "7" maxlength = "10" name="urn" class="form-control" id="urn" aria-describedby="emailHelp" Required>
            </div>
            <!-- <div class="form-group mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" maxlength = "20" name="username" class="form-control" id="username" aria-describedby="emailHelp" Required>
                <div id="emailHelp" class="form-text"> Use A Unique Username </div>
            </div> -->
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" minlength = "8" class="form-control" id="password" name="password" Required>
                <small id="emailHelp" class="form-text text-muted">The minimum length of a password should be 8 characters.</small>
            </div>
            <div class="form-group mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" Required>
                <small id="emailHelp" class="form-text text-muted">Make sure to type the same password</small>
            </div>
            <div class="text-center">
            <button type="submit" class="btn btn-primary">SignUp</button>
            </div>            
        </form>
  </div>
</div>
</div>
</section>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>