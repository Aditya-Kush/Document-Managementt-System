<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login.php");
    exit;
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

    <title>Welcome - <?php echo $_SESSION['urn']?></title>
  </head>
  <body>
    <?php require 'partials/_nav.php' ?>
    
    <div class="container my-3">
    <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">Welcome - <?php echo $_SESSION['urn']?></h4>
      <p>Welcome to NDA. You are logged in as <?php echo $_SESSION['urn']?>. Now Fill The Registration Form <a href="/NDA/Student_Registraton_Form.php">using this link.</a><br><small> If you already submit the form then <a href="/NDA/Check_Certification.php">use this link.</a> to download the certificate. </small></p> </p>
      <hr>
      <p class="mb-0">Whenever you need to, be sure to logout <a href="/NDA/logout.php"> using this link.</a></p>
    </div>
    <?php
$urn = $_SESSION['urn'];
$conn = mysqli_connect("localhost", "root", "", "users");

$sql = "SELECT fname, lname, file_path, status FROM `student-registeration` WHERE URN = '$urn'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='card mt-4'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Your Submission Details</h5>";

    echo "<p><strong>Name:</strong> " . $row['fname'] . " " . $row['lname'] . "</p>";

    echo "<p><strong>Uploaded File:</strong> ";
    if (!empty($row['file_path'])) {
      if($row['status'] == 'approved'){
        echo "<a href='teacher/" . $row['file_path'] . "' target='_blank'>View File</a></p>";
      }else {
        echo "<a href='" . $row['file_path'] . "' target='_blank'>View File</a></p>";
      }
    } else {
        echo "No file uploaded.</p>";
    }

    echo "<p><strong>Status:</strong> <span class='badge badge-";
    echo ($row['status'] == 'approved') ? "success" : (($row['status'] == 'rejected') ? "danger" : "secondary");
    echo "'>" . ucfirst($row['status']) . "</span></p>";

    echo "</div></div>";
}
?>


  </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>