<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $firstName = $_POST["firstname"];
           $lastName = $_POST["lastname"];
           $email = $_POST["email"];
           $mobileNumber = $_POST["mobilenumber"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($firstName) || empty($lastName) || empty($email) || empty($mobileNumber) || empty($password) || empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 characters long");
           }
           if ($password !== $passwordRepeat) {
            array_push($errors,"Passwords do not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount > 0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors) > 0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           } else {
            
            $token = bin2hex(random_bytes(32));
            $sql = "INSERT INTO users (firstname, lastname, email, mobilenumber, password, token) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "ssssss", $firstName, $lastName, $email, $mobileNumber, $passwordHash, $token);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            } else {
                die("Something went wrong");
            }
           }
          

        }
        ?>
        <form action="registration.php" method="post" id="registrationForm">
    <div class="form-group">
        <label for="firstname">First Name:</label>
        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" required minlength="2" maxlength="100">
    </div>
    <div class="form-group">
        <label for="lastname">Last Name:</label>
        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" required minlength="2" maxlength="100">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
    </div>
    <div class="form-group">
        <label for="mobilenumber">Mobile Number:</label>
        <input type="text" class="form-control" name="mobilenumber" id="mobilenumber" placeholder="Mobile Number" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required minlength="8" maxlength="20">
    </div>
    <div class="form-group">
        <label for="repeat_password">Confirm Password:</label>
        <input type="password" class="form-control" name="repeat_password" id="repeat_password" placeholder="Please re-enter your Password" required>
    </div>
    <div class="form-btn">
        <input type="submit" class="btn btn-primary" value="Register" name="submit">
    </div>
</form>
        <div>
        <div><p>Already had an account? <a href="login.php">Login</a></p></div>
      </div>
    </div>
</body>
</html>