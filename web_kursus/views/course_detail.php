<?php
$course_id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("
    SELECT c.*, t.name as tutor_name, t.bio as tutor_bio, t.expertise 
    FROM courses c 
    LEFT JOIN tutors t ON c.tutor_id = t.id 
    WHERE c.id = ?
");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    echo "<div class='course-section'><h2>Kursus tidak ditemukan</h2></div>";
    // include 'views/partials/footer.php'; // Consider if footer should be here
    exit;
}
?>

<div class="course-detail">
    <div class="course-detail-grid">
        <div class="course-main">
            <img src="<?php echo htmlspecialchars($course['image_url']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" 
                 style="width: 100%; height: 300px; object-fit: cover; border-radius: var(--radius); margin-bottom: 2rem;">
            
            <span class="course-category"><?php echo htmlspecialchars($course['category']); ?></span>
            <h1 style="font-size: 2.5rem; margin: 1rem 0; color: var(--text-dark);"><?php echo htmlspecialchars($course['title']); ?></h1>
            
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin: 1rem 0;">
                Rp <?php echo number_format($course['price'], 0, ',', '.'); ?>
            </div>
            
            <p style="color: var(--text-light); line-height: 1.8; margin-bottom: 2rem;">
                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
            </p>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <button type="submit" name="enroll" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                        Daftar Kursus Ini
                    </button>
                </form>
                
                <?php
                if (isset($_POST['enroll'])) {
                    $user_id = $_SESSION['user_id'];
                    
                    // Check if already enrolled to prevent duplicates
                    $check_stmt = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
                    $check_stmt->bind_param("ii", $user_id, $course_id);
                    $check_stmt->execute();
                    $is_enrolled = $check_stmt->get_result()->num_rows > 0;
                    
                    if ($is_enrolled) {
                         echo "<div style='background: #eef; color: #336; padding: 1rem; border-radius: var(--radius); margin-top: 1rem;'>
                            Anda sudah terdaftar di kursus ini. <a href='index.php?page=dashboard'>Lihat di dashboard</a>
                        </div>";
                    } else {
                        $insert_stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
                        $insert_stmt->bind_param("ii", $user_id, $course_id);
                        if ($insert_stmt->execute()) {
                            echo "<div style='background: #efe; color: #363; padding: 1rem; border-radius: var(--radius); margin-top: 1rem;'>
                                Berhasil mendaftar kursus! <a href='index.php?page=dashboard'>Lihat di dashboard</a>
                            </div>";
                        } else {
                            echo "<div style='background: #fee; color: #c33; padding: 1rem; border-radius: var(--radius); margin-top: 1rem;'>
                                Gagal mendaftar. Silakan coba lagi.
                            </div>";
                        }
                    }
                }
                ?>
            <?php else: ?>
                <p style="color: var(--text-light);">
                    <a href="index.php?page=login" style="color: var(--primary);">Login</a> untuk mendaftar kursus ini.
                </p>
            <?php endif; ?>
        </div>
        
        <div class="course-sidebar">
            <div class="tutor-info">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">Tentang Tutor</h3>
                <div style="background: var(--primary-light); padding: 1.5rem; border-radius: var(--radius);">
                    <h4 style="color: var(--primary-dark); margin-bottom: 0.5rem;"><?php echo htmlspecialchars($course['tutor_name']); ?></h4>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($course['expertise']); ?></p>
                    <p style="color: var(--text-light); font-size: 0.9rem; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($course['tutor_bio'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>