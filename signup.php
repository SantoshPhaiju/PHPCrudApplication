<?php

$showAlert = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];


    // Checking whether the username exists in database or not
    $existSql = "SELECT * FROM `users` WHERE username = '$username' AND password = '$pass'";
    $result = mysqli_query($conn, $existSql);
    $existRows = mysqli_num_rows($result);
    if ($existRows > 0) {

        $showError = "Username already exists";
    } else {
        if ($pass == $cpass) {
            // Storing the hash value
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`username`, `password`, `timestamp`) VALUES ('$username', '$hash', current_timestamp())";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
            }
        }else{
            $showError = "Passwords do not match.";
        }
    }
}


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Signup Page</title>
    <style>
         div.container{
            min-height: 80vh;
        }
    </style>
</head>

<body>

    <?php

    include 'nav.php';

    ?>

    <?php

    if($showAlert){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You account has been created. Now you can processed with login.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }

    if($showError){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '. $showError  .'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }



    ?>
    <div class="container my-3">
        <h1>Signup to our iNotes Website</h1>

        <form method="POST" action="/php tutorial course/phpcrud/signup.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username </label>
                <input type="text" class="form-control" id="username" name="username" id="username" aria-describedby="username">
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="pass">
            </div>
            <div class="mb-3">
                <label for="cpass" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpass" name="cpass">
            </div>

            <button type="submit" class="btn btn-primary">Signup</button>
        </form>
    </div>



    <?php

include 'footer.php';

?>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>

</html>