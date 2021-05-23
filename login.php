<!--
    Login page
-->

<?php
    Define('DirectAccess', TRUE);
    
    session_start();
    require_once('config.php');

    if(isset($_SESSION['loginUser'])){
        header("location:index.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {     
        $loginUser = $_POST['username'];
        $loginPassword = md5($_POST['password']);
        $captcha = $_POST['captcha'];
        
        $query = $pdo->prepare('SELECT personnel_id FROM personnel WHERE personnel_login = :loginUser and personnel_password = :loginPassword');
        $query->execute(array('loginUser' => $loginUser, 'loginPassword' => $loginPassword));

        if (!$query) {
            printf("Error: %s\n", $query->errorInfo());
            exit();
        }

        $count = $query->rowCount();
        
        if($count == 1 &&  $captcha == $_SESSION['captcha']) {
            $_SESSION['loginUser'] = $loginUser;
            echo "<script type='text/javascript'> document.location ='index.php'; </script>";
        }
        else {
            echo "<script>alert('Incorrect details');</script>";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/gradients.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <script src="assets/js/jquery.js"></script>   
    <script src="assets/js/bootstrap.js"></script>
</head>

<body>
    <div class="login-group absolute-center night-fade-gradient">
        <h5 class="text-center">Welcome</h5>
        </br>
        <form action = "" method = "post">
            <div class="form-group">
                <input name="username" type="username" class="form-control" id="name_input" required placeholder="Login">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" id="password_input" required placeholder="Password">
            </div>
            <div class="form-group row">
                <div class="form-group col-md-6">
                    <input name="captcha" class="form-control" id="inputCaptcha" required maxlength="5" placeholder="Captcha">
                </div>
                <label class="form-group col-md-6 col-form-label captcha" for="inputCaptcha">
                    <?php include('captcha.php'); ?>
                </label>
            </div>
            <input type="submit" class="btn btn-light btn-block" value="Login"/><br />
        </form>
    </div>
</body>
</html>