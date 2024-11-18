<?php
session_start();
$page_title = 'Resend Email Verification';
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

                <div class="card">
                    <div class="card-header">
                        <h5>Resend Email Verification</h5>
                    </div>
                    <div class="card-body">
                        <form action="resend_code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="resend_email_verify_btn" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>