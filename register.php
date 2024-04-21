<?php
include('./constant/layout/head.php');
include('./constant/connect.php');
session_start();

$errors = array();

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        if ($username == "") {
            $errors[] = "Username is required";
        }

        if ($password == "") {
            $errors[] = "Password is required";
        }
    } else {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $connect->query($sql);

        if ($result->num_rows == 0) {
            // Username doesn't exist, proceed with registration
            $password = md5($password);

            $insertSql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            $insertResult = $connect->query($insertSql);

            if ($insertResult) {
                // Registration successful
                ?>

                <div class="registration-success">
                    <h1>Registration Successful</h1>
                    <p>Click here to <a href="login.php">login</a>.</p>
                </div>
                <style>
                    .registration-success {
                        background: linear-gradient(135deg, #8a2be2, #4b0082);
                        color: #fff;
                        padding: 20px;
                        text-align: center;
                        font-size: 30px;
                        display: block;
                    }

                    #main-wrapper {
                        display: none;
                    }
                </style>

            <?php
        } else {
            // Registration failed
            ?>
                <div class="popup popup--icon -error js_error-popup popup--visible">
                    <div class="popup__background"></div>
                    <div class="popup__content">
                        <h3 class="popup__content__title">
                            Error
                        </h3>
                        <p>Registration failed. Please try again later.</p>
                        <p>
                            <a href="login.php"><button class="button button--error" data-for="js_error-popup">Close</button></a>
                        </p>
                    </div>
                </div>

            <?php
        }
    } else {
        // Username already exists
        ?>

            <div class="popup popup--icon -error js_error-popup popup--visible">
                <div class="popup__background"></div>
                <div class="popup__content">
                   <center><h3 class="popup__content__title">
                        Error
                    </h3>
                    <p>Username already exists.</p>
                    <p>
                        <a href="login.php"><button class="button button--error" data-for="js_error-popup">Click here for Sign-in</button></a>
                    </p></center> 
                </div>
            </div>

    <?php
}
}
}

?>

<div id="main-wrapper">
    <div class="unix-login">
        <div class="container-fluid" style="background-image: url('./assets/uploadImage/Logo/banner2.jpg');
 background-color: #cccccc;">
            <div class="row justify-content-center">
                <div class="col-lg-3">
                    <div class="login-content card">
                        <div class="login-form">
                            <center><img src="./assets/uploadImage/Logo/logo3.png" style="width: 100%;"></center><br>
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="registerForm">
                                <div class="form-group">
                                    <label lass="col-sm-3 control-label">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
                                </div>
                                <button type="submit" name="register" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                            </form>
                            <div class="forgot-phone text-right f-right">
                                <a href="login.php" class="text-right f-w-600">Already have an account? Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/lib/jquery/jquery.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/popper.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="./assets/js/jquery.slimscroll.js"></script>
<script src="./assets/js/sidebarmenu.js"></script>
<script src="./assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="./assets/js/custom.min.js"></script>
</body>
</html>
