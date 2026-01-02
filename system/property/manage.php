<?php
ob_start();
session_start();
if (!isset($_SESSION['USERID'])) {
    header('Location:login.php');
}
include '../../init.php';
?>
<div class="row">
    <div calss="col-md-12">
        <a href="add_property.php" class="btn btn-default">Add Property</a>
        <a href="add_category.php" class="btn btn-default">Add Category</a>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="form-group">
                <label for="category">Select Category</label>
                <select name="category" id="category" class="form-control">
                    <option value="">--Select ALL--</option>
                    <?php
                    $db = dbConn();
                    $sql = "SELECT * FROM category";
                    $result = $db->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <option value="<?= $row['id'] ?>"><?= $row['cat_name'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Bed Rooms</label>
                <input type="number" name="bedrooms" class="form-control" />

            </div>
            <button type="submit" class="btn btn-info mb-2">Search</button>

        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php

        $where = null;
        extract($_POST);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($category)) {
                $where .= " p.category_id='$category' AND";
            }
            if (!empty($bedrooms)) {
                $where .= " p.bed='$bedrooms' AND";
            }
            

            if (!empty($where)) {
                $where = substr($where, 0, -3);
                $where = " WHERE " . $where;
            }
        }



        $db = dbConn();
        $sql = "SELECT * FROM property p left join category c on p.category_id=c.id $where";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
        ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Property Name</th>
                        <th>Property Address</th>
                        <th>Property Price</th>
                        <th>Sqft</th>
                        <th>Bedrooms</th>
                        <th>Bathrooms</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['cat_name'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $row['sqft'] ?></td>
                            <td><?= $row['bed'] ?></td>
                            <td><?= $row['bath'] ?></td>
                            <td><img src="../uploads/<?= $row['image'] ?>" width="100"></td>
                            <td><a href="edit_property.php?id=<?= $row['id'] ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_property.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete this record..?')">Delete</a>
                            </td>

                        </tr>

                    <?php
                    }
                    ?>
                </tbody>

            </table>

        <?php
        } else {
            echo "<div class='alert alert-info'>No Record Found..!</div>";
        }
        ?>
    </div>
</div>
<?php
$page_title = "Property Management";
$path = "Property";
$file = "Manage";
$content = ob_get_clean();
include '../layout.php';
?>