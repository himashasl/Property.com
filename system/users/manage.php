<?php
ob_start();
session_start();
if (!isset($_SESSION['USERID'])) {
    header('Location:login.php');
}
include '../../init.php';

extract($_POST);
?>
<div class="row">
    <div class="col-12">
        <!-- Search Form Start -->
        <form class="form-inline mb-3" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="form-group mr-2">
                <input type="text" class="form-control" name="firstname" placeholder="First Name" value="<?= isset($firstname) ? htmlspecialchars($firstname) : '' ?>">
            </div>
            <div class="form-group mr-2">
                <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="<?= isset($lastname) ? htmlspecialchars($lastname) : '' ?>">
            </div>
            <div class="form-group mr-2">
                <select class="form-control" name="usertype">
                    <option value="">All User Types</option>
                    <?php
                    $db = dbConn();
                    $usql = "SELECT id, description FROM user_types";
                    $ures = $db->query($usql);
                    while ($utype = $ures->fetch_assoc()) {
                        $selected = (isset($usertype) && $usertype == $utype['id']) ? 'selected' : '';
                        echo "<option value=\"{$utype['id']}\" $selected>{$utype['description']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <!-- Search Form End -->
        <a href="add.php" class="btn btn-success mb-2"><i class="fa fa-plus-circle" aria-hidden="true"></i> New User</a>

        <div class="card">
            <div class="card-header bg-warning">



            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">


                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = dbConn();
                        // Build WHERE clause for search
                        $WHERE = NULL;

                        if (!empty($firstname)) {
                            $WHERE .= " FirstName LIKE '%$firstname%' AND";
                        }
                        if (!empty($lastname)) {
                            $WHERE .= " LastName LIKE '%$lastname%' AND";
                        }
                        if (!empty($usertype)) {
                            $WHERE .= " UserType = '$usertype' AND";
                        }

                        $WHERE = rtrim($WHERE, ' AND');
                        if (!empty($WHERE)) {
                            $WHERE = " WHERE " . $WHERE;
                        } else {
                            $WHERE = "";
                        }

                        $sql = "SELECT users.*,user_types.description as usertype,status.description as sts  FROM users LEFT JOIN user_types ON users.UserType=user_types.id LEFT JOIN status ON status.id=users.Status $WHERE ";
                        $result = $db->query($sql);
                        ?>

                        <?php
                        if ($result->num_rows > 0) {
                            $i = 1;
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><img src="../uploads/<?= $row['Photo'] ?>" alt="" class="img-fluid" width="100"></td>
                                    <td><?= $row['FirstName'] ?></td>
                                    <td><?= $row['LastName'] ?></td>
                                    <td><?= $row['usertype'] ?></td>
                                    <td><?= $row['sts'] ?></td>
                                    <td>
                                        <form action="edit.php" method="post">
                                            <input type="hidden" name="UserId" value="<?= $row['UserId'] ?>">
                                            <button type="submit" name="action" value="edit" class="btn btn-success">Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="delete.php" method="post">
                                            <input type="hidden" name="UserId" value="<?= $row['UserId'] ?>">
                                            <button type="submit" name="action" value="delete" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                                $i++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<?php
$page_title = "User Management";
$path = "Users";
$file = "Manage";
$content = ob_get_clean();
include '../layout.php';
?>