<?php

$login = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '_dbconnect.php';
    $username = $_POST['username'];
    $pass = $_POST['pass'];


    $sql = "SELECT * FROM `users` WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    // fetching number of rows
    $num = mysqli_num_rows($result);

    if($num == 1){
        while($row = mysqli_fetch_assoc($result)){
            if(password_verify($pass, $row['password'])){
                $user_id = $row['user_id'];
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("location: phpcrud.php?loginsuccess=true&userid=$user_id");
            }else{
                $showError = "Invalid Credentials";
            }
        }
    }else{
        $showError = "Invalid Credentials";
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

    <title>Login</title>
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


    if(isset($_GET['logoutsuccess']) && $_GET['logoutsuccess'] == true){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You have been logged out successfully. 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }


    if ($showError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> ' . $showError  . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }



    ?>
    <div class="container my-3">
        <h1>Login to your account.</h1>

        <form method="POST" action="/php tutorial course/phpcrud/login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username </label>
                <input type="text" class="form-control" id="username" name="username" id="username" aria-describedby="username">
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="pass">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>




<?php

include 'footer.php';

?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>

</html>