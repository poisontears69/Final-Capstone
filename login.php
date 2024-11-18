<?php 
session_start();

if(isset($_SESSION['authenticated']))
{
    $_SESSION['error'] = "You are already logged in";
    header('Location: dashboard.php');
    exit(0);
}

$page_title = 'Login Page';
include('includes/header.php');
include('includes/navbar.php'); 
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="process_login.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="login_now_btn" class="btn btn-primary">Login</button>

                                <a href="password_reset.php" class="float-end">Forgot Password?</a>
                            </div>
                        </form>

                        <hr>
                        <h5>
                            Did not get an email?
                            <a href="resend_email_verification.php">Resend</a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>