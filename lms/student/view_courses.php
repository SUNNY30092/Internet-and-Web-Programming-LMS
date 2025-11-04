<?php
require '../includes/config.php';
require_login();

if ($_SESSION['role'] !== 'student') { 
    header('Location: /lms/'); 
    exit; 
}

include '../includes/header.php';

$student_id = $_SESSION['user_id'];

if (isset($_POST['enroll'])) {
    $cid = (int)$_POST['course_id'];

    $chk = $conn->query("SELECT * FROM enrollments WHERE course_id={$cid} AND student_id={$student_id}");
    if ($chk->num_rows == 0) {
        $conn->query("INSERT INTO enrollments (course_id, student_id) VALUES ({$cid}, {$student_id})");
        echo '<p class="msg success">✅ Enrolled successfully.</p>';
    } else {
        echo '<p class="msg info">ℹ️ Already enrolled in this course.</p>';
    }
}
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $safe_search = $conn->real_escape_string($search);
    $res = $conn->query("
        SELECT c.*, u.username AS teacher_name 
        FROM courses c 
        LEFT JOIN users u ON c.teacher_id = u.id
        WHERE c.title LIKE '%$safe_search%' 
           OR u.username LIKE '%$safe_search%'
    ");
} else {
    $res = $conn->query("
        SELECT c.*, u.username AS teacher_name 
        FROM courses c 
        LEFT JOIN users u ON c.teacher_id = u.id
    ");
}
?>

<style>
    body {
        background-color: #0d0d0d;
        color: #f0f0f0;
        font-family: "Segoe UI", sans-serif;
        margin: 0;
        padding-top: 80px;
    }

    h2 {
        text-align: center;
        color: #00bfff;
        font-weight: 600;
        margin-bottom: 30px;
    }

    .courses-container {
        max-width: 1000px;
        margin: 0 auto;
        background: #1a1a1a;
        border-radius: 15px;
        padding: 30px;
    }

    form.search-bar {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    input[type="text"] {
        padding: 10px 15px;
        border: 1px solid #333;
        border-radius: 8px;
        width: 60%;
        background-color: #111;
        color: white;
    }

    input::placeholder {
        color: #888;
    }

    button, a.clear-btn {
        background-color: #00bfff;
        color: black;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 500;
    }

    button:hover, a.clear-btn:hover {
        background-color: #00ffaa;
    }

    a.clear-btn {
        text-decoration: none;
        display: inline-block;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .card {
        background-color: #111;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 20px;
    }

    .card-title {
        font-size: 1.2rem;
        color: #00bfff;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .card-text {
        color: #ccc;
        font-size: 0.95rem;
        margin-bottom: 10px;
    }

    .teacher {
        font-size: 0.9rem;
        color: #aaa;
        margin-bottom: 15px;
    }

    .enroll-btn {
        background-color: #00ffaa;
        color: black;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        cursor: pointer;
        font-weight: 600;
    }

    .enroll-btn:hover {
        background-color: #00e0a0;
    }

    .msg {
        text-align: center;
        padding: 10px;
        border-radius: 10px;
        margin: 10px auto;
        max-width: 600px;
    }

    .success {
        background-color: #003300;
        color: #00ffaa;
    }

    .info {
        background-color: #001f33;
        color: #00bfff;
    }

    p.no-results {
        text-align: center;
        color: #aaa;
    }
</style>

<div class="courses-container">
    <h2>🎓 Available Courses</h2>

    <form method="get" class="search-bar">
        <input type="text" name="search" value="<?php echo esc($search); ?>" placeholder="Search by course title or teacher name">
        <button type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a href="view_courses.php" class="clear-btn">Clear</a>
        <?php endif; ?>
    </form>

    <div class="courses-grid">
        <?php 
        if ($res->num_rows > 0):
            while($row = $res->fetch_assoc()): 
        ?>
            <div class="card">
                <div class="card-title"><?php echo esc($row['title']); ?></div>
                <div class="card-text"><?php echo esc(substr($row['description'], 0, 150)); ?></div>
                <div class="teacher">👨‍🏫 <?php echo esc($row['teacher_name'] ?? '-'); ?></div>
                <form method="post">
                    <input type="hidden" name="course_id" value="<?php echo esc($row['id']); ?>">
                    <button class="enroll-btn" name="enroll">Enroll</button>
                </form>
            </div>
        <?php 
            endwhile;
        else:
            echo '<p class="no-results">No courses found.</p>';
        endif;
        ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
