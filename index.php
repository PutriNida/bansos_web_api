<?php
session_start();
include 'global/config.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pengajuan Dana Bantuan Kec. Kepenuhan</title>

    <!-- Bootstrap -->
    <link href="./assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="./assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="./assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="./assets/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="./assets/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
    <div>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <img src="./assets/images/logo_kabupaten_rokan_hulu.png" width="30%" hight="30%" />
                    <h5>Pengajuan Dana Bantuan Kec. Kepenuhan</h5>
                    <form method="POST" action="actions/act_login.php">
                        <h1>Login</h1>
                        <?php
                        if (!empty($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible " role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span>
                                </button>
                                <?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <input type="text" class="form-control" placeholder="Username" required="" name="username"
                                autocomplete="false" />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" required=""
                                name="password" autocomplete="false" />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Log in</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>

</html>