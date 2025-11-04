<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Simple LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{padding-top:70px}

:root {
    --dark-bg: #0a0a0a;
    --darker-bg: #000000;
    --glass-white: rgba(255, 255, 255, 0.05);
    --glass-white-hover: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.1);
    --text-primary: #ffffff;
    --text-secondary: #a0a0a0;
    --accent: #ffffff;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: var(--darker-bg);
    color: var(--text-primary);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.6;
}

.navbar {
    background: var(--dark-bg) !important;
    border-bottom: 1px solid var(--glass-border);
    backdrop-filter: blur(20px);
}

.navbar-brand {
    color: var(--text-primary) !important;
    font-weight: 700;
    font-size: 1.5rem;
}

.nav-link {
    color: var(--text-secondary) !important;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: var(--text-primary) !important;
}

.card, .dashboard-container, .courses-container, 
.table-container, .form-container, .upload-container,
.materials-container, .material-card {
    background: var(--glass-white) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border) !important;
    border-radius: 16px !important;
    transition: all 0.3s ease;
}

.card:hover, .material-card:hover {
    background: var(--glass-white-hover) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-4px);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 24px;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: var(--accent) !important;
    color: var(--darker-bg) !important;
    border: none !important;
}

.btn-primary:hover {
    background: #e0e0e0 !important;
    transform: translateY(-2px);
}

.btn-success, .btn-secondary {
    background: var(--glass-white) !important;
    color: var(--text-primary) !important;
    border: 1px solid var(--glass-border) !important;
}

.btn-success:hover, .btn-secondary:hover {
    background: var(--glass-white-hover) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-2px);
}

.btn-danger {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #ff6b6b !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
}

.btn-danger:hover {
    background: rgba(239, 68, 68, 0.2) !important;
    border-color: rgba(239, 68, 68, 0.4) !important;
}

.form-control, .form-select {
    background: var(--glass-white) !important;
    border: 1px solid var(--glass-border) !important;
    color: var(--text-primary) !important;
    border-radius: 8px;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    background: var(--glass-white-hover) !important;
    border-color: rgba(255, 255, 255, 0.3) !important;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.05) !important;
    color: var(--text-primary) !important;
}

.form-control::placeholder {
    color: var(--text-secondary);
}

.table {
    color: var(--text-primary) !important;
    background: transparent !important;
    border-collapse: separate;
    border-spacing: 0;
}

.table thead {
    background: var(--glass-white-hover) !important;
}

.table thead th {
    border: none !important;
    color: var(--text-primary) !important;
    font-weight: 600;
    padding: 16px;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1px;
}

.table tbody tr {
    border-bottom: 1px solid var(--glass-border) !important;
    transition: background 0.2s ease;
}

.table tbody tr:hover {
    background: var(--glass-white) !important;
}

.table tbody td {
    padding: 16px;
    border: none !important;
}

.alert {
    background: var(--glass-white) !important;
    border: 1px solid var(--glass-border) !important;
    border-radius: 8px;
    color: var(--text-primary) !important;
    padding: 16px;
}

h1, h2, h3, h4, h5, h6 {
    color: var(--text-primary);
    font-weight: 700;
    margin-bottom: 1rem;
}

h2 {
    font-size: 2rem;
}

a {
    color: var(--text-primary);
    text-decoration: none;
    transition: opacity 0.3s ease;
}

a:hover {
    opacity: 0.8;
}

.dashboard-container, .courses-container,
.table-container, .form-container, .upload-container,
.materials-container {
    padding: 32px;
    margin: 32px auto;
    max-width: 1200px;
}

.material-card {
    padding: 24px;
    margin-bottom: 24px;
}

.container {
    max-width: 1200px;
}

@media (max-width: 768px) {
    .dashboard-container, .courses-container,
    .table-container, .form-container, .upload-container {
        padding: 20px;
        margin: 20px 10px;
    }
    
    h2 {
        font-size: 1.5rem;
    }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
<div class="container-fluid">
<a class="navbar-brand" href="/lms/">SimpleLMS</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navmenu">
<ul class="navbar-nav ms-auto">
<?php if(isset($_SESSION['user_id'])): ?>
<li class="nav-item"><a class="nav-link" href="/lms/dashboard.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="/lms/logout.php">Logout</a></li>
<?php else: ?>
<li class="nav-item"><a class="nav-link" href="/lms/login.php">Login</a></li>
<li class="nav-item"><a class="nav-link" href="/lms/register.php">Register</a></li>
<?php endif; ?>
</ul>
</div>
</div>
</nav>
<div class="container">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
