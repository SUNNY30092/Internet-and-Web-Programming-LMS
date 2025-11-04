<?php
require_once 'includes/config.php';
include 'includes/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$username = $conn->real_escape_string(trim($_POST['username']));
$email = $conn->real_escape_string(trim($_POST['email']));
$password = $_POST['password'];
$role = in_array($_POST['role'], ['student','teacher']) ? $_POST['role'] : 'student';


if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($username) >= 3 && strlen($password) >= 6) {
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username,email,password,role) VALUES ('{$username}','{$email}','{$hash}','{$role}')";
if ($conn->query($sql)) {
echo '<div class="alert alert-success">Registration successful. <a href="login.php">Login now</a>.</div>';
} else {
echo '<div class="alert alert-danger">Error: ' . esc($conn->error) . '</div>';
}
} else {
echo '<div class="alert alert-warning">Please check your inputs.</div>';
}
}
?>


<div class="row justify-content-center">
<div class="col-md-6">
<h2>Register</h2>
<form method="post">
<div class="mb-3"><input class="form-control" name="username" placeholder="Username" required></div>
<div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
<div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password (min 6)" required></div>
<div class="mb-3">
<select class="form-select" name="role">
<option value="student">Student</option>
<option value="teacher">Teacher</option>
</select>
</div>
<button class="btn btn-primary">Register</button>
</form>
</div>
</div>


<?php include 'includes/footer.php'; ?>