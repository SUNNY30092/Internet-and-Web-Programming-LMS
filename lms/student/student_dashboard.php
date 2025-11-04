<?php
require '../includes/config.php';
require_login();

if ($_SESSION['role'] !== 'student') {
    header('Location: /lms/');
    exit;
}

include '../includes/header.php';

$student_id = $_SESSION['user_id'];

if (isset($_GET['unenroll'])) {
    $course_id = intval($_GET['unenroll']);
    $check = $conn->query("SELECT * FROM enrollments WHERE student_id={$student_id} AND course_id={$course_id}");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM enrollments WHERE student_id={$student_id} AND course_id={$course_id}");
        echo "<div class='msg success'> Successfully unenrolled from the course.</div>";
    } else {
        echo "<div class='msg error'> Invalid course unenroll attempt.</div>";
    }
}

$res = $conn->query("
    SELECT c.*, u.username AS teacher_name 
    FROM courses c
    JOIN enrollments e ON c.id = e.course_id
    JOIN users u ON c.teacher_id = u.id
    WHERE e.student_id = {$student_id}
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background: linear-gradient(135deg, #000000, #1a1a1a);
    color: #fff;
    padding-top: 90px; /* fixes header overlap */
}
.dashboard {
    max-width: 950px;
    margin: 40px auto;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 20px;
    padding: 30px 40px;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 8px 25px rgba(0,0,0,0.6);
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #fff;
}
.top-btn {
    text-align: center;
    margin-bottom: 25px;
}
a.btn {
    display: inline-block;
    background: rgba(255,255,255,0.1);
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 500;
    transition: 0.3s;
    border: 1px solid rgba(255,255,255,0.2);
}
a.btn:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
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
.btn-view, .btn-delete {
    padding: 8px 15px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    border-radius: 10px;
    text-decoration: none;
    transition: 0.3s;
}
.btn-view:hover {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}
.btn-delete {
    color: #ff6b6b;
}
.btn-delete:hover {
    background: rgba(255, 0, 0, 0.3);
    transform: translateY(-2px);
}
.no-courses {
    text-align: center;
    color: #aaa;
    padding: 20px;
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
</style>
</head>
<body>

<div class="dashboard">
    <h2>🎓 Student Dashboard</h2>
    <div class="top-btn">
        <a class="btn" href="view_courses.php">🔍 Browse Courses</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Title</th>
                <th>Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($res->num_rows > 0): ?>
            <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                <td>
                    <a href="view_materials.php?course_id=<?php echo $row['id']; ?>" class="btn-view">📚 View Materials</a>
                    <a href="?unenroll=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to unenroll from this course?');"> Remove</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" class="no-courses">No enrolled courses found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php include '../includes/footer.php'; ?>
