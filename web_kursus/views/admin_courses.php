<?php
// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$message = '';

// Handle POST actions (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $course_id = (int)($_POST['course_id'] ?? 0);
    
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $tutor_id = (int)$_POST['tutor_id'];
    $price = (float)$_POST['price'];
    $image_url = $_POST['image_url'];

    if ($action === 'create') {
        $stmt = $conn->prepare("INSERT INTO courses (title, description, category, tutor_id, price, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiis", $title, $description, $category, $tutor_id, $price, $image_url);
        if ($stmt->execute()) {
            $message = "Kursus berhasil ditambahkan!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } elseif ($action === 'update' && $course_id > 0) {
        $stmt = $conn->prepare("UPDATE courses SET title=?, description=?, category=?, tutor_id=?, price=?, image_url=? WHERE id=?");
        $stmt->bind_param("sssiisi", $title, $description, $category, $tutor_id, $price, $image_url, $course_id);
        if ($stmt->execute()) {
            $message = "Kursus berhasil diperbarui!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    // Redirect to avoid form resubmission
    header("Location: index.php?page=admin_courses&message=" . urlencode($message));
    exit;
}

// Handle GET action (Delete)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $course_id = (int)$_GET['id'];
    if ($course_id > 0) {
        // We should also delete related enrollments to maintain data integrity
        $delete_enrollments_stmt = $conn->prepare("DELETE FROM enrollments WHERE course_id = ?");
        $delete_enrollments_stmt->bind_param("i", $course_id);
        $delete_enrollments_stmt->execute();

        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $course_id);
        if ($stmt->execute()) {
            $message = "Kursus berhasil dihapus!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    header("Location: index.php?page=admin_courses&message=" . urlencode($message));
    exit;
}


// Fetch all courses
$courses = $conn->query("
    SELECT c.*, t.name as tutor_name 
    FROM courses c 
    LEFT JOIN tutors t ON c.tutor_id = t.id 
    ORDER BY c.id DESC
");

// Display feedback message
if (isset($_GET['message'])) {
    echo '<div style="background: #efe; color: #363; padding: 1rem; border-radius: var(--radius); margin: 1rem;">' . htmlspecialchars($_GET['message']) . '</div>';
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Kelola Kursus</h2>
                <a href="index.php?page=admin_course_form" class="btn btn-primary">Tambah Kursus</a>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tutor</th>
                        <th>Harga</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($courses->num_rows > 0): ?>
                        <?php while($course = $courses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $course['id']; ?></td>
                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo htmlspecialchars($course['category']); ?></td>
                            <td><?php echo htmlspecialchars($course['tutor_name']); ?></td>
                            <td>Rp <?php echo number_format($course['price'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="index.php?page=admin_course_form&id=<?php echo $course['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <a href="index.php?page=admin_courses&action=delete&id=<?php echo $course['id']; ?>" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?');" 
                                   class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Belum ada data kursus.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
