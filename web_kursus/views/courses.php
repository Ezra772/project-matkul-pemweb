<div class="course-section">
    <div class="section-title">
        <h2>Semua Kursus</h2>
        <p>Temukan kursus yang sesuai dengan minat dan karirmu</p>
    </div>

    <?php
    // Simple search and filter
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    
    $query = "SELECT c.*, t.name as tutor_name FROM courses c LEFT JOIN tutors t ON c.tutor_id = t.id WHERE 1=1";
    $params = [];
    $types = '';
    
    if (!empty($search)) {
        $query .= " AND (c.title LIKE ? OR c.description LIKE ?)";
        $search_param = "%" . $search . "%";
        $params[] = &$search_param;
        $params[] = &$search_param;
        $types .= 'ss';
    }
    
    if (!empty($category)) {
        $query .= " AND c.category = ?";
        $params[] = &$category;
        $types .= 's';
    }
    
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <!-- Simple Search -->
    <form method="GET" style="margin-bottom: 2rem; display: flex; gap: 1rem; justify-content: center;">
        <input type="hidden" name="page" value="courses">
        <input type="text" name="search" placeholder="Cari kursus..." value="<?php echo htmlspecialchars($search); ?>" 
               style="padding: 12px; border: 2px solid var(--border); border-radius: var(--radius); width: 300px;">
        <select name="category" style="padding: 12px; border: 2px solid var(--border); border-radius: var(--radius);">
            <option value="">Semua Kategori</option>
            <option value="Web Development" <?php echo $category == 'Web Development' ? 'selected' : ''; ?>>Web Development</option>
            <option value="Data Science" <?php echo $category == 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
            <option value="UI/UX Design" <?php echo $category == 'UI/UX Design' ? 'selected' : ''; ?>>UI/UX Design</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <div class="course-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($course = $result->fetch_assoc()): ?>
            <div class="course-card">
                <img src="<?php echo $course['image_url']; ?>" alt="Gambar kursus <?php echo htmlspecialchars($course['title']); ?>" class="course-image">
                <div class="course-content">
                    <span class="course-category"><?php echo $course['category']; ?></span>
                    <h3 class="course-title"><?php echo $course['title']; ?></h3>
                    <p class="course-tutor">Oleh: <?php echo $course['tutor_name']; ?></p>
                    <p style="color: var(--text-light); margin-bottom: 1rem; font-size: 0.9rem;">
                        <?php echo substr($course['description'], 0, 100) . '...'; ?>
                    </p>
                    <div class="course-price">Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></div>
                    <a href="index.php?page=course_detail&id=<?php echo $course['id']; ?>" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1/-1; color: var(--text-light);">
                Tidak ada kursus yang ditemukan.
            </p>
        <?php endif; ?>
    </div>
</div>