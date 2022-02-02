<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
}

$insert = false;
$update = false;
$delete = false;

// Connect to the database
include '_dbconnect.php';

if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $delete = true;
    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        // Update the record
        $sno = $_POST["snoEdit"];
        $title = $_POST["titleEdit"];
        $description = $_POST["descriptionEdit"];

        // Sql query to be executed
        $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $update = true;
        } else {
            echo "We could not update the record successfully";
        }
    } else {

        $title = $_POST["title"];
        $description = $_POST["description"];
        $id = $_GET['userid'];

        // Adding the data to the database.
        $sql = "INSERT INTO `notes` (`title`, `description`, `note_user_id`) VALUES ('$title', '$description', '$id')";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            $insert = true;
        } else {
            echo "The data could not be added due to this error --->" . mysqli_error($conn);
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

    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <title>iNotes - Keep your notes online</title>
</head>

<body>

    <!-- Edit modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
  Edit modal
</button> -->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="/php tutorial course/phpcrud/phpcrud.php?userid=<?php echo $_GET['userid'] ?>"" method="POST">
                        <input type="hidden" id="snoEdit" name="snoEdit">
                        <div class="mb-3">
                            <label for="title" class="form-label">Note title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Note Descripition</label>
                            <textarea type="text" class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update note</button>
                    </form>

                </div>
            </div>
        </div>
    </div>



    <?php


    include 'nav.php';

    ?>




    <?php

    if (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] == "true") {
        $username = $_SESSION['username'];
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> You have been loggedin with '. $username .'
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
    }


    if ($insert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your Note has been inserted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    }
    ?>
    <?php
    if ($update) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your Note has been updated successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    }
    ?>
    <?php
    if ($delete) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your Note has been deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    }
    ?>




    <div class="container my-4">
        <h1 class="text-center">Welcome - <?php  echo $_SESSION['username'] ?></h1>
        <h2>Add Your note here...</h2>
        <form action="/php tutorial course/phpcrud/phpcrud.php?userid=<?php echo $_GET['userid'] ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Note title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Note Descripition</label>
                <textarea type="text" class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add note</button>
        </form>
    </div>
    <hr>





    <div class="container my-5">
        <h1 class="my-5">Your notes are here...</h1>
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $id = $_GET['userid'];



                $sql = "SELECT * FROM `notes` WHERE note_user_id='$id'";
                $result = mysqli_query($conn, $sql);
                $sno = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                    <th scope='row'>" . $sno . " </th>
                    <td>" . $row['title'] . " </td>
                    <td> " . $row['description'] . " </td>
                    <td><button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button></td>
                </tr>";
                    $sno = $sno + 1;
                }
                ?>


            </tbody>
        </table>

    </div>
    




    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src=" //cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>



    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ", );
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                console.log(title, description);
                titleEdit.value = title;
                descriptionEdit.value = description;
                snoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle');

            })
        });

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to delete this note!")) {
                    console.log("yes");
                    window.location = `/php tutorial course/phpcrud/phpcrud.php?delete=${sno}&userid=<?php echo $_GET['userid'] ?>`;
                    // TODO: Create a form and use post request to submit a form
                } else {
                    console.log("no");
                }
            })
        });
    </script>

<?php

include 'footer.php';

?>

</body>

</html>