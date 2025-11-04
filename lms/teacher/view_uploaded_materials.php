<?php
session_start();
include('../includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';

$teacher_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'] ?? 0;

if (isset($_GET['delete'])) {
    $material_id = intval($_GET['delete']);
    $res = $conn->query("SELECT file_path FROM materials WHERE id={$material_id} AND uploaded_by={$teacher_id}");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $file_path = $row['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $conn->query("DELETE FROM materials WHERE id={$material_id}");
        echo "<div class='msg success'> Material deleted successfully!</div>";
    } else {
        echo "<div class='msg error'> Invalid delete action.</div>";
    }
}

$query = "SELECT * FROM materials WHERE course_id={$course_id} AND uploaded_by={$teacher_id}";
$res = $conn->query($query);

$student_count = 0;
$count_query = $conn->query("SELECT COUNT(*) AS total FROM enrollments WHERE course_id={$course_id}");
if ($count_query && $count_query->num_rows > 0) {
    $student_count = $count_query->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Uploaded Materials</title>
<style>
body {
    background: linear-gradient(135deg, #000, #1a1a1a);
    color: #fff;
    font-family: "Poppins", sans-serif;
    padding-top: 90px;
}
.container {
    max-width: 900px;
    margin: auto;
    background: rgba(0,0,0,0.6);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.6);
    position: relative;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
.students-count {
    position: absolute;
    top: 30px;
    right: 30px;
    background: rgba(255,255,255,0.1);
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 500;
    color: #7db4ff;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(8px);
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
th {
    background: rgba(255,255,255,0.1);
}
tr:hover {
    background: rgba(255,255,255,0.1);
}
a {
    color: #7db4ff;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
.btn-delete {
    color: #ff6b6b;
    text-decoration: none;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 5px 10px;
    border-radius: 8px;
    transition: 0.3s;
}
.btn-delete:hover {
    background: rgba(255,0,0,0.3);
    transform: translateY(-2px);
}
.msg {
    text-align: center;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 20px;
}
.msg.success {
    background: rgba(0, 255, 0, 0.2);
}
.msg.error {
    background: rgba(255, 0, 0, 0.2);
}
.back {
    display: block;
    margin-top: 25px;
    text-align: center;
    color: #7db4ff;
    text-decoration: none;
}
.back:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="container">
    <div class="students-count">📊 Students Enrolled: <?php echo $student_count; ?></div>
    <h2>📂 Uploaded Materials</h2>

    <?php if ($res->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>File</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><a href="<?php echo $row['file_path']; ?>" target="_blank">Open</a></td>
                <td><?php echo strtoupper($row['file_type']); ?></td>
                <td>
                    <a href="?course_id=<?php echo $course_id; ?>&delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this material?')">🗑 Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align:center; color:#aaa;">No materials uploaded yet for this course.</p>
    <?php endif; ?>

    <a href="teacher_dashboard.php" class="back">⬅ Back to Dashboard</a>
</div>

</body>
</html>
