<?php
require_once 'includes/config.php';
include 'includes/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = $conn->real_escape_string(trim($_POST['email']));
$password = $_POST['password'];


$res = $conn->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
if ($res && $res->num_rows) {
$user = $res->fetch_assoc();
if (password_verify($password, $user['password'])) {
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];


if ($user['role'] === 'admin') {
header('Location: admin/admin_dashboard.php');
} elseif ($user['role'] === 'teacher') {
header('Location: teacher/teacher_dashboard.php');
} else {
header('Location: student/student_dashboard.php');
}
exit;
} else {
echo '<div class="alert alert-danger">Invalid credentials.</div>';
}
} else {
echo '<div class="alert alert-danger">User not found.</div>';
}
}
?>


<div class="row justify-content-center">
<div class="col-md-5">
<h2>Login</h2>
<form method="post">
<div class="mb-3"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
<div class="mb-3"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
<button class="btn btn-primary">Login</button>
</form>
</div>
</div>


<?php include 'includes/footer.php'; ?>