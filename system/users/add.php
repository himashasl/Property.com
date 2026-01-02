<?php
ob_start();
session_start();
if (!isset($_SESSION['USERID'])) {
    header('Location:login.php');
}
include '../../init.php';
?>
<div class="card card-primary">
    <div class="card-header">

    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <?php

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        extract($_POST);
        $FirstName = dataClean($FirstName);
        $LastName = dataClean($LastName);
        $UserName = dataClean($UserName);

        $messages = array();

        if (empty($FirstName)) {
            $messages['FirstName'] = "The First Name should not be blank..!";
        }
        if (empty($LastName)) {
            $messages['LastName'] = "The Last Name should not be blank..!";
        }
        if (empty($UserName)) {
            $messages['UserName'] = "The User Name should not be blank..!";
        }
        if (empty($UserType)) {
            $messages['UserType'] = "The User Type should not be blank..!";
        }
        if (empty($Password)) {
            $messages['Password'] = "The Password should not be blank..!";
        }
        if (empty($Status)) {
            $messages['Status'] = "The Status should not be blank..!";
        }

        if (!empty($UserName)) {
            $db = dbConn();
            $sql = "SELECT * FROM users WHERE UserName='$UserName'";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                $messages['UserName'] = "Invalid User Name..!";
            }
        }

        if (empty($messages)) {
            $file = $_FILES['photo'];
            $name = $file['name'];
            $tmp = $file['tmp_name'];
            $size = $file['size'];
            $error = $file['error'];

            $ext = explode(".", $name);
            $ext = strtolower(end($ext));

            $allowed = array("jpg", "png");

            if (in_array($ext, $allowed)) {
                if ($error === 0) {

                    if ($size < 45056546) {
                        $filename = uniqid("", true) . "." . $ext;
                        $location = "../uploads/$filename";

                        if (move_uploaded_file($tmp, $location)) {
                        } else {
                            $messages['photo'] = "File has upload error...!";
                        }
                    } else {
                        $messages['photo'] = "The File size has not valid...!";
                    }
                } else {
                    $messages['photo'] = "File has error...!";
                }
            } else {
                $messages['photo'] = "Not allowed file type...!";
            }
        }



        if (empty($messages)) {

            $db = dbConn();
            $Password = password_hash($Password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users(UserName,Password,FirstName,LastName,UserType,Status,Photo) VALUES('$UserName','$Password','$FirstName','$LastName','$UserType','$Status','$filename')";
            $db->query($sql);

            header("Location:manage.php");
        }
    }

    ?>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
        <div class="card-body">
            <div class="form-group">
                <label for="FirstName">First Name</label>
                <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="Enter First Name" value="<?= @$FirstName ?>">
                <span class="text-danger"><?= @$messages['FirstName'] ?></span>
            </div>
            <div class="form-group">
                <label for="LastName">Last Name</label>
                <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Enter Last Name" value="<?= @$LastName ?>">
                <span class="text-danger"><?= @$messages['LastName'] ?></span>
            </div>
            <div class="form-group">

                <label for="UserType">Select User Type</label>
                <select class="form-control" name="UserType" id="UserType">
                    <option value="">--</option>
                    <?php
                    $db = dbConn();
                    $sql = "SELECT * FROM user_types";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                    ?>
                            <option value="<?= $row['id'] ?>" <?php if ($row['id'] == @$UserType) {
                                                                    echo 'selected';
                                                                } ?>><?= $row['description'] ?></option>
                    <?php

                        }
                    }
                    ?>

                </select>
                <span class="text-danger"><?= @$messages['UserType'] ?></span>
            </div>
            <div class="form-group">
                <label for="UserName">User Name</label>
                <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Enter User Name" value="<?= @$UserName ?>">
                <span class="text-danger"><?= @$messages['UserName'] ?></span>
            </div>
            <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" class="form-control" id="Password" name="Password" placeholder="Password">
                <span class="text-danger"><?= @$messages['Password'] ?></span>
            </div>
            <div class="form-group">
                <label for="Status">Select Status</label>
                <select class="form-control" name="Status" id="Status">
                    <option value="">--</option>
                    <?php
                    $db = dbConn();
                    $sql = "SELECT * FROM status";
                    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                    ?>
                            <option value="<?= $row['id'] ?>" <?php if ($row['id'] == @$Status) {
                                                                    echo 'selected';
                                                                } ?>><?= $row['description'] ?></option>
                    <?php

                        }
                    }
                    ?>

                </select>
                <span class="text-danger"><?= @$messages['Status'] ?></span>
            </div>
            <div class="form-group">
                <label for="">Upload Your Image</label>
                <input type="file" name="photo" id="photo">
                <span class="text-danger"><?= @$messages['photo'] ?></span>
            </div>


        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
<?php
$page_title = "User Management";
$path = "Users";
$file = "New User";
$content = ob_get_clean();
include '../layout.php';
?>