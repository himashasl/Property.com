<?php
session_start();
include '../init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Property.com | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Property</b>.Com</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <?php


                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    extract($_POST);

                    $UserName = dataClean($UserName);

                    $messages = array();

                    if (empty($UserName)) {
                        $messages['UserName'] = "The User Name should not be blank...!";
                    }

                    if (empty($Password)) {
                        $messages['Password'] = "The Password should not be blank...!";
                    }

                    if (empty($messages)) {
                        $db = dbConn();
                        $sql = "SELECT * FROM users WHERE UserName='$UserName'";
                        $result = $db->query($sql);

                        if ($result->num_rows == 1) {
                            $row = $result->fetch_assoc();

                            $_SESSION['USERID'] = $row['UserId'];
                            $_SESSION['FNAME'] = $row['FirstName'];
                            $_SESSION['LNAME'] = $row['LastName'];

                            if (password_verify($Password, $row['Password'])) {
                                header("Location:index.php");
                            } else {
                                $messages['Password'] = "Invalid User Name or Password...!";
                            }
                        } else {
                            $messages['Password'] = "Invalid User Name or Password...!";
                        }
                    }
                }

                if (!empty($messages)) {

                ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                        <p><?= @$messages['UserName'] ?></p>
                        <p><?= @$messages['Password'] ?></p>

                    </div>
                <?php
                }

                ?>

                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="email" id="UserName" name="UserName" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="Password" name="Password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>



                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>