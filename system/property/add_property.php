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
            $category_id = dataClean($category_id);
            $PropertyName = dataClean($PropertyName);
            $PropertyAddress = dataClean($PropertyAddress);
            $PropertyPrice = dataClean($PropertyPrice);
            $Sqft = dataClean($Sqft);
            $Bedrooms = dataClean($Bedrooms);
            $Bathrooms = dataClean($Bathrooms);

            if (empty($category_id)) {
                $messages['category_id'] = "The Category should not be blank..!";
            }
            if (empty($PropertyName)) {
                $messages['PropertyName'] = "The Property Name should not be blank..!";
            }
            if (empty($PropertyAddress)) {
                $messages['PropertyAddress'] = "The Property Address should not be blank..!";
            }
            if (empty($PropertyPrice)) {
                $messages['PropertyPrice'] = "The Property Price should not be blank..!";
            }
            if (empty($Sqft)) {
                $messages['Sqft'] = "The Sqft should not be blank..!";
            }
            if (empty($Bedrooms)) {
                $messages['Bedrooms'] = "The Bedrooms should not be blank..!";
            }
            if (empty($Bathrooms)) {
                $messages['Bathrooms'] = "The Bathrooms should not be blank..!";
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
                $filename = $_FILES['image']['name'];
                $filetype = $_FILES['image']['type'];
                $filesize = $_FILES['image']['size'];

                // Verify file extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!array_key_exists($ext, $allowed)) {
                    $messages['image'] = "Error: Please select a valid file format.";
                }

                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if ($filesize > $maxsize) {
                    $messages['image'] = "Error: File size is larger than the allowed limit.";
                }

                // Verify MYME type of the file
                if (in_array($filetype, $allowed)) {
                    // Check whether file exists before uploading it
                    if (file_exists("../uploads/" . $filename)) {
                        $messages['image'] = $filename . " is already exists.";
                    } else {
                        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $filename);
                    }
                } else {
                    $messages['image'] = "Error: There was a problem uploading your file. Please try again.";
                }
            } else {
                $messages['image'] = "Error: " . $_FILES['image']['error'];
            }
            if (empty($messages)) {
                $db = dbConn();
                $sql = "INSERT INTO property (category_id, name, address, price, sqft, bed, bath, image) VALUES ('$category_id', '$PropertyName', '$PropertyAddress', '$PropertyPrice', '$Sqft', '$Bedrooms', '$Bathrooms', '$filename')";
                $db->query($sql);

                header('Location:manage.php');
            }
        }

        ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="CategoryName">Category Name</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Select Category</option>
                    <?php
                    $db = dbConn();
                    $sql = "SELECT * FROM category";
                    $result = $db->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['cat_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <span class="text-danger"><?= @$messages['category_id'] ?></span>
            </div>
            <div class="form-group">
                <label for="PropertyTitle">Property Name</label>
                <input type="text" class="form-control" id="PropertyName" name="PropertyName" placeholder="Enter Property Name">
                <span class="text-danger"><?= @$messages['PropertyName'] ?></span>
            </div>
            <div class="form-group">
                <label for="PropertyDescription">Property Address</label>
                <textarea class="form-control" id="PropertyAddress" name="PropertyAddress" placeholder="Enter Property Address"></textarea>
                <span class="text-danger"><?= @$messages['PropertyAddress'] ?></span>
            </div>
            <div class="form-group">
                <label for="PropertyPrice">Property Price</label>
                <input type="text" class="form-control" id="PropertyPrice" name="PropertyPrice" placeholder="Enter Property Price">
                <span class="text-danger"><?= @$messages['PropertyPrice'] ?></span>
            </div>
            <div class="form-group">
                <label for="Sqft">Sqft</label>
                <input type="text" class="form-control" id="Sqft" name="Sqft" placeholder="Enter Sqft">
                <span class="text-danger"><?= @$messages['Sqft'] ?></span>

            </div>
            <div class="form-group">
                <label for="Bedrooms">Bedrooms</label>
                <input type="text" class="form-control" id="Bedrooms" name="Bedrooms" placeholder="Enter Bedrooms">
                <span class="text-danger"><?= @$messages['Bedrooms'] ?></span>
            </div>
            <div class="form-group">
                <label for="Bathrooms">Bathrooms</label>
                <input type="text" class="form-control" id="Bathrooms" name="Bathrooms" placeholder="Enter Bathrooms">
                <span class="text-danger"><?= @$messages['Bathrooms'] ?></span>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <span class="text-danger"><?= @$messages['image'] ?></span>

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