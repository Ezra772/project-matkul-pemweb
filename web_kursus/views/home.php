<?php
// Include auth controller for potential features
include 'controllers/auth.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Wujudkan Karir Digital Impianmu</h1>
        <p>Belajar dari para expert dengan kurikulum terkini. Siap untuk masa depan digital.</p>
        <div class="hero-actions">
            <a href="index.php?page=courses" class="btn btn-primary">Jelajahi Kursus</a>
            <a href="index.php?page=register" class="btn btn-secondary">Mulai Belajar</a>
        </div>
    </div>
</section>

<section class="course-section">
    <div class="section-title">
        <h2>Kursus Populer</h2>
        <p>Pilih jalur karirmu dan mulai belajar hari ini</p>
    </div>
    
    <div class="course-grid">
        <?php
        // Get featured courses
        $result = $conn->query("
            SELECT c.*, t.name as tutor_name 
            FROM courses c 
            LEFT JOIN tutors t ON c.tutor_id = t.id 
            LIMIT 3
        ");
        
        while ($course = $result->fetch_assoc()):
        ?>
        <div class="course-card">
            <img src="<?php echo $course['image_url']; ?>" alt="Gambar kursus <?php echo htmlspecialchars($course['title']); ?>" class="course-image">
            <div class="course-content">
                <span class="course-category"><?php echo $course['category']; ?></span>
                <h3 class="course-title"><?php echo $course['title']; ?></h3>
                <p class="course-tutor">Oleh: <?php echo $course['tutor_name']; ?></p>
                <div class="course-price">Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></div>
                <a href="index.php?page=course_detail&id=<?php echo $course['id']; ?>" class="btn btn-primary">Lihat Detail</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>