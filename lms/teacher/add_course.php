<?php
require '../includes/config.php';
require_login();

if ($_SESSION['role'] !== 'teacher') {
    header('Location: /lms/');
    exit;
}

include '../includes/header.php';

$teacher_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string(trim($_POST['title']));
    $desc = $conn->real_escape_string(trim($_POST['description']));
    $conn->query("INSERT INTO courses (title, description, teacher_id) VALUES ('{$title}', '{$desc}', {$teacher_id})");
    echo '<div class="alert-success">✅ Course added successfully!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Course</title>
<style>
    :root { --header-height: 90px; }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: linear-gradient(135deg, #000000, #1a1a1a);
        font-family: "Poppins", sans-serif;
        color: #fff;
        min-height: 100vh;
        padding-top: calc(var(--header-height) + 20px);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h2 {
        font-size: 30px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 25px;
        color: #fff;
        text-shadow: 0 0 8px rgba(255,255,255,0.1);
    }

    .form-container {
        width: 90%;
        max-width: 500px;
        padding: 30px;
        border-radius: 20px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 8px 28px rgba(0,0,0,0.6);
    }

    label {
        display: block;
        font-weight: 500;
        color: #ddd;
        margin-bottom: 8px;
    }

    input[type="text"], textarea {
        width: 100%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 20px;
        font-size: 15px;
        outline: none;
        transition: all 0.2s ease;
    }

    input[type="text"]:focus, textarea:focus {
        border-color: rgba(255,255,255,0.4);
        background: rgba(255,255,255,0.12);
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    button {
        width: 100%;
        background: rgba(255,255,255,0.1);
        color: white;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 12px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    button:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    .alert-success {
        text-align: center;
        margin: 20px auto;
        padding: 12px 16px;
        background: rgba(0, 255, 100, 0.15);
        border: 1px solid rgba(0, 255, 100, 0.3);
        color: #b6ffc0;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        backdrop-filter: blur(6px);
    }

    @media (max-width: 600px) {
        h2 { font-size: 24px; }
        .form-container { padding: 20px; }
    }
</style>
</head>
<body>

<h2>➕ Add New Course</h2>

<div class="form-container">
    <form method="post">
        <label>Course Title</label>
        <input type="text" name="title" placeholder="Enter course title" required>

        <label>Description</label>
        <textarea name="description" placeholder="Enter a short description (optional)"></textarea>

        <button type="submit">Add Course</button>
    </form>
</div>

</body>
</html>

<?php include '../includes/footer.php'; ?>
