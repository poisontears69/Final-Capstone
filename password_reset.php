<?php
session_start();

$page_title = 'Password Reset';
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
                        <h5>Reset Password</h5>
                    </div>
                    <div class="card-body p-4">

                        <form action="password_reset_code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email Adress</label>
                                <input type="text" name="email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="password_reset_link" class="btn btn-primary">Send Password Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</div>