<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';

$message = "";

if (isset($_POST['upload'])) {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $uploaded_by = $_SESSION['user_id'];

    $file = $_FILES['file'];
    $filename = basename($file['name']);
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $allowed_types = ['pdf', 'docx', 'pptx', 'mp4', 'avi', 'mov'];
    if (in_array($filetype, $allowed_types)) {
        $folder = in_array($filetype, ['mp4', 'avi', 'mov']) ? '../uploads/videos/' : '../uploads/documents/';
        $target_file = $folder . time() . '_' . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO materials (course_id, title, description, filename, file_path, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssi", $course_id, $title, $description, $filename, $target_file, $filetype, $uploaded_by);

            if ($stmt->execute()) {
                $message = "<div class='alert-success'> File uploaded successfully!</div>";
            } else {
                $message = "<div class='alert-error'> Database error: " . htmlspecialchars($stmt->error) . "</div>";
            }
        } else {
            $message = "<div class='alert-error'> File upload failed.</div>";
        }
    } else {
        $message = "<div class='alert-warning'> Invalid file type. Allowed: PDF, DOCX, PPTX, MP4, AVI, MOV.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Course Material</title>
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

    .upload-container {
        width: 90%;
        max-width: 600px;
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

    select, input[type="text"], textarea, input[type="file"] {
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

    select option {
        background: #000;
        color: #fff;
    }

    input[type="text"]:focus, textarea:focus, select:focus {
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

    .alert-success, .alert-error, .alert-warning {
        text-align: center;
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 10px;
        backdrop-filter: blur(8px);
        font-weight: 500;
    }

    .alert-success {
        background: rgba(0, 255, 100, 0.15);
        border: 1px solid rgba(0, 255, 100, 0.3);
        color: #b6ffc0;
    }

    .alert-error {
        background: rgba(255, 50, 50, 0.15);
        border: 1px solid rgba(255, 50, 50, 0.3);
        color: #ffb6b6;
    }

    .alert-warning {
        background: rgba(255, 255, 0, 0.15);
        border: 1px solid rgba(255, 255, 0, 0.3);
        color: #fff7b6;
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        text-decoration: none;
        color: #ccc;
        font-weight: 500;
        transition: 0.2s;
    }

    .back-link:hover {
        color: #fff;
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        h2 { font-size: 24px; }
        .upload-container { padding: 20px; }
    }
</style>
</head>
<body>

<h2>📤 Upload Course Material</h2>

<div class="upload-container">
    <?php echo $message; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="course">Select Course:</label>
        <select name="course_id" id="course" required>
            <option value="">-- Select a Course --</option>
            <?php
            $teacher_id = $_SESSION['user_id'];
            $res = $conn->query("SELECT * FROM courses WHERE teacher_id = $teacher_id");
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['title']) . "</option>";
                }
            } else {
                echo "<option disabled>No courses found</option>";
            }
            ?>
        </select>

        <label>Title:</label>
        <input type="text" name="title" placeholder="Enter material title" required>

        <label>Description:</label>
        <textarea name="description" placeholder="Enter a short description (optional)"></textarea>

        <label>File:</label>
        <input type="file" name="file" required>

        <button type="submit" name="upload">Upload</button>
    </form>
</div>

<a href="teacher_dashboard.php" class="back-link">⬅ Back to Dashboard</a>

</body>
</html>
