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
    extract($_POST);

    if ($_SERVER['REQUEST_METHOD'] == "POST" && @$action == 'edit') {

        $db = dbConn();
        $sql = "SELECT * FROM users WHERE UserId='$UserId'";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();

        $UserId = $row['UserId'];
        $FirstName = $row['FirstName'];
        $LastName = $row['LastName'];
        $UserName = $row['UserName'];
        $UserType = $row['UserType'];
        $Status = $row['Status'];
    }


    if ($_SERVER['REQUEST_METHOD'] == "POST" && @$action == 'update') {

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

        if (empty($Status)) {
            $messages['Status'] = "The Status should not be blank..!";
        }

        if (!empty($UserName)) {
            $db = dbConn();
            $sql = "SELECT * FROM users WHERE UserName='$UserName' AND UserId !='$UserId'";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                $messages['UserName'] = "Invalid User Name..!";
            }
        }

        if (empty($messages)) {

            $db = dbConn();
           
            $sql = "UPDATE users SET UserName='$UserName',FirstName='$FirstName',LastName='$LastName',UserType='$UserType',Status='$Status' WHERE UserId='$UserId'";
            $db->query($sql);

            header("Location:manage.php");
        }
    }

    ?>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
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


        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <input type="hidden" name="UserId" value="<?= $UserId ?>">
            <button type="submit" name="action" value="update" class="btn btn-primary">Submit</button>
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