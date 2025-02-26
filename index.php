<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit(); // Make sure to exit after redirecting
}

// Fetch username from session
$username = $_SESSION["user"]["firstname"]; // Assuming the username is stored in the "firstname" field
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Homepage</title>
</head>
<body>
    <div class="container">
        <h1>Hello, <?php echo $username; ?>! Welcome sa bahay ni kuya!</h1>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>
</body>
</html>
