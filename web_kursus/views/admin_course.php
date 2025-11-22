<?php
// Check if admin
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Handle add course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $tutor_id = $_POST['tutor_id'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    
    $conn->query("INSERT INTO courses (title, description, category, tutor_id, price, image_url) 
                  VALUES ('$title', '$description', '$category', $tutor_id, $price, '$image_url')");
    
    $success_message = "Kursus berhasil ditambahkan!";
}
?>

<div class="admin-panel">
    <h1 style="margin-bottom: 2rem; color: var(--text-dark);">Kelola Kursus</h1>
    
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
            <?php if (isset($success_message)): ?>
                <div style="background: #efe; color: #363; padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Form Tambah Kursus -->
                <div>
                    <h3 style="margin-bottom: 1rem;">Tambah Kursus Baru</h3>
                    <form method="POST" style="background: var(--bg-light); padding: 1.5rem; border-radius: var(--radius);">
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Judul Kursus</label>
                            <input type="text" name="title" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Deskripsi</label>
                            <textarea name="description" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius); height: 80px;"></textarea>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kategori</label>
                            <select name="category" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                                <option value="Web Development">Web Development</option>
                                <option value="Data Science">Data Science</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tutor</label>
                            <select name="tutor_id" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                                <?php
                                $tutors = $conn->query("SELECT * FROM tutors");
                                while ($tutor = $tutors->fetch_assoc()) {
                                    echo "<option value='{$tutor['id']}'>{$tutor['name']} - {$tutor['expertise']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Harga</label>
                            <input type="number" name="price" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">URL Gambar</label>
                            <input type="url" name="image_url" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);" 
                                   placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <button type="submit" name="add_course" class="btn btn-primary">Tambah Kursus</button>
                    </form>
                </div>
                
                <!-- Daftar Kursus -->
                <div>
                    <h3 style="margin-bottom: 1rem;">Daftar Kursus</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $courses = $conn->query("SELECT * FROM courses ORDER BY id DESC");
                            while ($course = $courses->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $course['title']; ?></td>
                                <td><?php echo $course['category']; ?></td>
                                <td>Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>