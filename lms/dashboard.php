<?php
require 'includes/config.php';
require_login();
include 'includes/header.php';


$role = $_SESSION['role'];
if ($role === 'admin') header('Location: admin/admin_dashboard.php');
if ($role === 'teacher') header('Location: teacher/teacher_dashboard.php');
if ($role === 'student') header('Location: student/student_dashboard.php');


include 'includes/footer.php';
?>