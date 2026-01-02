<?php
ob_start();
session_start();
if (!isset($_SESSION['USERID'])) {
    header('Location:login.php');
}
include '../../init.php';
?>

<div class="row">
    <div class="col-md-12">
        <a href="add_property.php" class="btn btn-default">Add Property</a>
        <a href="add_category.php" class="btn btn-default">Add Category</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php

        extract($_POST);
        $messages = array();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $CategoryName = dataClean($CategoryName);
            if (empty($CategoryName)) {
                $messages['CategoryName'] = "The Category Name should not be blank..!";
            }

            if (empty($messages)) {
                $db = dbConn();
                $sql = "INSERT INTO category (cat_name) VALUES ('$CategoryName')";
                $db->query($sql);

                header('Location:manage.php');
            }
        }

        ?>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

            <div class="form-group">
                <label for="CategoryName">Category Name</label>
                <input type="text" class="form-control" id="CategoryName" name="CategoryName" placeholder="Enter Category Name">
                <span class="text-danger"><?= @$messages['CategoryName'] ?></span>
            </div>



            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>





<?php
$page_title = "Property Management";
$path = "Property";
$file = "Manage";
$content = ob_get_clean();
include '../layout.php';
?>