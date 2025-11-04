<?php include('includes/config.php');
include('includes/header.php'); ?>


<div class="row">
    <div class="col-md-8">
        <h1>Welcome to Simple LMS</h1>
        <p>Learn. Teach. Share.</p>
    </div>
    <div class="col-md-4">
        <?php if(!is_logged_in()): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="color: white;">Get started</h5>
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="register.php" class="btn btn-secondary">Register</a>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="color: white;">Hello, <?php echo esc($_SESSION['username']); ?></h5>
                <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
