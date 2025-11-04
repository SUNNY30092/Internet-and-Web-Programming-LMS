<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';

$student_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Materials</title>
    <style>
        body {
            background-color: #0d0d0d;
            font-family: "Segoe UI", sans-serif;
            color: white;
            padding: 40px 20px;
            margin: 0;
            padding-top: 100px;
        }

        h2 {
            text-align: center;
            color: #00bfff;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .materials-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 12px;
        }

        .material-card {
            background-color: #111;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .material-card h5 {
            color: #00bfff;
            margin: 0 0 8px;
        }

        .material-card em {
            color: #bbb;
        }

        .material-card p {
            color: #ccc;
            margin: 5px 0;
        }

        .video-preview {
            border-radius: 8px;
            margin-top: 10px;
            width: 100%;
            max-width: 700px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .download-link {
            display: inline-block;
            margin-top: 8px;
            color: #00ffaa;
            text-decoration: none;
            font-weight: 500;
        }

        .download-link:hover {
            color: #00bfff;
        }

        .msg {
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            max-width: 600px;
            margin: 10px auto;
        }

        .info {
            background-color: #002233;
            color: #00bfff;
        }

        .error {
            background-color: #330000;
            color: #ff4d4d;
        }

        .back-btn {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            padding: 10px 20px;
            background-color: #111;
            border: 1px solid #333;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .back-btn:hover {
            background-color: #00bfff;
            color: #000;
        }
    </style>
</head>
<body>

<h2>📚 Course Materials</h2>

<div class="materials-container">
<?php
if ($course_id) {
    $stmt = $conn->prepare("SELECT * FROM materials WHERE course_id = ? ORDER BY uploaded_at DESC");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $file_link = str_replace('../', '', $row['file_path']);
            echo "<div class='material-card'>
                    <h5>" . htmlspecialchars($row['title']) . "</h5>
                    <p><em>" . htmlspecialchars($row['description']) . "</em></p>
                    <p>📅 Uploaded on: " . htmlspecialchars($row['uploaded_at']) . "</p>";

            $file_ext = strtolower($row['file_type']);
            if (in_array($file_ext, ['mp4', 'avi', 'mov'])) {
                echo "<video class='video-preview' controls>
                        <source src='../$file_link' type='video/$file_ext'>
                      </video>";
            } else {
                echo "<a class='download-link' href='../$file_link' target='_blank'>📄 View / Download Material</a>";
            }

            echo "</div>";
        }
    } else {
        echo "<p class='msg info'>No materials uploaded yet for this course.</p>";
    }
} else {
    echo "<p class='msg error'>Invalid course selected.</p>";
}
?>
</div>

<a href='student_dashboard.php' class='back-btn'>⬅ Back to Dashboard</a>

</body>
</html>
