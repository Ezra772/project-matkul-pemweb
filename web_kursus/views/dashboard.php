<?php
// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}
?>

<div class="course-section">
    <h2 style="margin-bottom: 1rem;">Dashboard - <?php echo $_SESSION['username']; ?></h2>
    <p style="color: var(--text-light); margin-bottom: 2rem;">Selamat datang di dashboard belajarmu!</p>

    <div style="background: var(--bg-white); padding: 2rem; border-radius: var(--radius); margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Kursus Saya</h3>
        
        <?php
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("
            SELECT c.*, e.enrolled_at 
            FROM enrollments e 
            JOIN courses c ON e.course_id = c.id 
            WHERE e.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $enrollments = $stmt->get_result();
        
        if ($enrollments->num_rows > 0): 
        ?>
            <div class="course-grid">
                <?php while ($course = $enrollments->fetch_assoc()): ?>
                <div class="course-card">
                    <img src="<?php echo $course['image_url']; ?>" class="course-image">
                    <div class="course-content">
                        <span class="course-category"><?php echo $course['category']; ?></span>
                        <h3 class="course-title"><?php echo $course['title']; ?></h3>
                        <p style="color: var(--text-light); font-size: 0.9rem;">
                            Terdaftar: <?php echo date('d M Y', strtotime($course['enrolled_at'])); ?>
                        </p>
                        <a href="index.php?page=course_detail&id=<?php echo $course['id']; ?>" class="btn btn-primary">Lanjut Belajar</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-light); padding: 2rem;">
                Anda belum terdaftar di kursus manapun. 
                <a href="index.php?page=courses" style="color: var(--primary);">Jelajahi kursus</a>
            </p>
        <?php endif; ?>
    </div>
</div>