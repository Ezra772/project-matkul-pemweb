<?php
// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Fetch tutors for dropdown
$tutors = $conn->query("SELECT id, name FROM tutors ORDER BY name");

$course_id = (int)($_GET['id'] ?? 0);
$is_edit = $course_id > 0;
$course = null;
$page_title = $is_edit ? "Edit Kursus" : "Tambah Kursus Baru";

if ($is_edit) {
    $stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();
    if (!$course) {
        // Handle course not found
        echo "<p>Kursus tidak ditemukan.</p>";
        exit;
    }
}
?>

<div class="admin-panel">
     <div class="admin-grid">
        <div class="admin-sidebar">
            <nav>
                <a href="index.php?page=admin">Dashboard</a>
                <a href="index.php?page=admin_courses" class="active">Kelola Kursus</a>
                <a href="index.php?page=admin_users">Kelola User</a>
                <a href="index.php?page=admin_tutors">Kelola Tutor</a>
            </nav>
        </div>
        
        <div class="admin-content">
            <h2 style="margin-bottom: 2rem;"><?php echo $page_title; ?></h2>
            
            <form method="POST" action="index.php?page=admin_courses" class="form-container" style="max-width: 800px;">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">

                <div class="form-group">
                    <label for="title">Judul Kursus</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Web Development" <?php echo ($course['category'] ?? '') == 'Web Development' ? 'selected' : ''; ?>>Web Development</option>
                            <option value="Data Science" <?php echo ($course['category'] ?? '') == 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                            <option value="UI/UX Design" <?php echo ($course['category'] ?? '') == 'UI/UX Design' ? 'selected' : ''; ?>>UI/UX Design</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tutor_id">Tutor</label>
                        <select id="tutor_id" name="tutor_id" required>
                            <option value="">Pilih Tutor</option>
                            <?php while($tutor = $tutors->fetch_assoc()): ?>
                                <option value="<?php echo $tutor['id']; ?>" <?php echo ($course['tutor_id'] ?? '') == $tutor['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tutor['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($course['price'] ?? '0'); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image_url">URL Gambar</label>
                    <input type="" id="image_url" name="image_url" value="<?php echo htmlspecialchars($course['image_url'] ?? ''); ?>">
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php?page=admin_courses" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
