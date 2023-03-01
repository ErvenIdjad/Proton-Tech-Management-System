<?php
session_start();
require_once '../homeIncludes/dbconfig.php';
require_once '../tools/variables.php';
$page_title = 'ProtonTech | Sign Up';
include_once('../homeIncludes/header.php');

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get the form data and sanitize it
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $usertype = 'customer';

    // Prepare and execute the first SQL statement to insert email, password, and user type into the accounts table
    $stmt = mysqli_prepare($conn, "INSERT INTO accounts (email, password, user_type) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $email, $password, $usertype);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Get the ID of the newly inserted account
    $account_id = mysqli_insert_id($conn);

    // Prepare and execute the second SQL statement to insert the rest of the data into the customer table
    $stmt = mysqli_prepare($conn, "INSERT INTO customer (account_id, fname, mname, lname, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isssss", $account_id, $fname, $mname, $lname, $phone, $address);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['signup_success'] = true;

    header("Location: login.php");
    exit();
}
?>


<body>
    <?php include_once('../homeIncludes/homenav.php');?>
    <div class="register-photo">
        <div class="form-container">
            <form action="signup.php" class="form" method="POST" id="repair-form" enctype="multipart/form-data">
                <h2 class="text-center login-h4"><strong>Sign Up</strong><img class="login-img"
                        src="../img/proton-logo.png" alt=""></h2>
                <div class="progressbar">
                    <div class="progress" id="progress"></div>
                    <div class="progress-step progress-step-active" data-title="Name"></div>
                    <div class="progress-step" data-title="Contact"></div>
                    <div class="progress-step" data-title="Electronic"></div>
                </div>
                <div class="form-step form-step-active">
                    <div class="form-group lgns"><input class="form-control" type="text" name="fname"
                            placeholder="First Name">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group lgns"><input class="form-control" type="text" name="mname"
                            placeholder="Middle Name">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group lgns"><input class="form-control" type="text" name="lname"
                            placeholder="Last Name">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group btn-block btn-block2">
                        <a href="#" class="btn btn-primary btn-next width-50 ml-auto btn-block"><i
                                class="fa fa-chevron-right"></i></a>
                    </div><a href="../login/login.php" class="already">You already have an account? Login here.</a>
                </div>

                <div class="form-step">
                    <div class="form-group lgns"><input class="form-control" type="email" name="email"
                            placeholder="Email">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group lgns"><input class="form-control" type="password" name="password"
                            placeholder="Password">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group lgns"><input class="form-control" type="password" name="password-repeat"
                            placeholder="Confirm password">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group btn-block btn-block2">
                        <a href="#" class="btn btn-primary width-50 btn-prev"><i class="fa fa-chevron-left"></i></a>
                        <a href="#" class="btn btn-primary width-50 btn-next"><i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="form-step">
                    <div class="form-group lgns"><input class="form-control" type="tel" name="phone"
                            placeholder="Phone">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group lgns"><input class="form-control" type="text" name="address"
                            placeholder="Address">
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group lgns"><label class="form-label" for="eimg">Profile Image</label>
                        <input type="file" class="form-control" id="eimg" name="eimg" />
                        <span class="val-error"></span>
                    </div>
                    <div class="form-group btn-block btn-block2">
                        <a href="#" class="btn btn-primary width-50 btn-prev" id="adis"><i
                                class="fa fa-chevron-left"></i></a>
                        <input type="submit" value="SUBMIT" class="btn btn-primary btn-submit confirm" name="submit"
                            id="btn-submit">
                    </div>
                </div>
            </form>
        </div>

    </div>
    </div>
</body>

</html>