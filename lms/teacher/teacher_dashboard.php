<?php
require '../includes/config.php';
require_login();

if ($_SESSION['role'] !== 'teacher') {
    header('Location: /lms/');
    exit;
}

include '../includes/header.php';

$teacher_id = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $course_id = intval($_GET['delete']);

    $check = $conn->query("SELECT id FROM courses WHERE id={$course_id} AND teacher_id={$teacher_id}");
    if ($check->num_rows > 0) {
        $res_mat = $conn->query("SELECT file_path FROM materials WHERE course_id={$course_id}");
        while ($row = $res_mat->fetch_assoc()) {
            $file_path = $row['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $conn->query("DELETE FROM materials WHERE course_id={$course_id}");
        $conn->query("DELETE FROM courses WHERE id={$course_id}");
        echo "<div class='msg success'>✅ Course deleted successfully!</div>";
    } else {
        echo "<div class='msg error'>❌ Invalid course delete request.</div>";
    }
}

$res = $conn->query("SELECT * FROM courses WHERE teacher_id={$teacher_id} ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Teacher Dashboard</title>
<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #000, #1a1a1a);
    color: #fff;
    padding-top: 90px; 
}
.dashboard {
    max-width: 1000px;
    margin: 0 auto;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 20px;
    padding: 30px;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.6);
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #fff;
    font-weight: 600;
}
.top-btns {
    text-align: center;
    margin-bottom: 25px;
}
.btn {
    background: rgba(255,255,255,0.15);
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 500;
    transition: 0.3s;
    margin: 0 6px;
    display: inline-block;
}
.btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}
.table-container {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
}
th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
th {
    background: rgba(255,255,255,0.1);
    font-weight: 600;
}
tr:hover {
    background: rgba(255,255,255,0.1);
}
.no-courses {
    text-align: center;
    color: #aaa;
    padding: 20px;
}
.btn-view, .btn-delete {
    padding: 8px 15px;
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-view {
    background: rgba(255,255,255,0.08);
    color: #fff;
}
.btn-view:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}
.btn-delete {
    background: rgba(255, 0, 0, 0.15);
    color: #ff6b6b;
}
.btn-delete:hover {
    background: rgba(255, 0, 0, 0.35);
    transform: translateY(-2px);
}
.msg {
    text-align: center;
    padding: 10px;
    border-radius: 10px;
    margin: 15px auto;
    max-width: 600px;
}
.msg.success {
    background: rgba(0, 255, 0, 0.15);
}
.msg.error {
    background: rgba(255, 0, 0, 0.15);
}
</style>
</head>
<body>

<div class="dashboard">
    <h2>👨‍🏫 Teacher Dashboard</h2>
    <div class="top-btns">
        <a href="add_course.php" class="btn">➕ Add New Course</a>
        <a href="upload_material.php" class="btn">📤 Upload Materials</a>
    </div>

    <div class="table-container">
        <h4 style="text-align:center; color:#bbb; margin-bottom:10px;">Your Courses</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res->num_rows > 0): ?>
                    <?php while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <a href="view_uploaded_materials.php?course_id=<?php echo $row['id']; ?>" class="btn-view">📂 View Materials</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this course? This will remove all its materials too.')">🗑 Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="no-courses">No courses found. Create one to get started!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php include '../includes/footer.php'; ?>
