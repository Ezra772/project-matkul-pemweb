<?php
// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$tutor_id = (int)($_GET['id'] ?? 0);
$is_edit = $tutor_id > 0;
$tutor = null;
$page_title = $is_edit ? "Edit Tutor" : "Tambah Tutor Baru";

if ($is_edit) {
    $stmt = $conn->prepare("SELECT * FROM tutors WHERE id = ?");
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    $tutor = $stmt->get_result()->fetch_assoc();
    if (!$tutor) {
        echo "<p>Tutor tidak ditemukan.</p>";
        exit;
    }
}
?>

<div class="admin-panel">
     <div class="admin-grid">
        <div class="admin-sidebar">
            <nav>
                <a href="index.php?page=admin">Dashboard</a>
                <a href="index.php?page=admin_courses">Kelola Kursus</a>
                <a href="index.php?page=admin_users">Kelola User</a>
                <a href="index.php?page=admin_tutors" class="active">Kelola Tutor</a>
            </nav>
        </div>
        
        <div class="admin-content">
            <h2 style="margin-bottom: 2rem;"><?php echo $page_title; ?></h2>
            
            <form method="POST" action="index.php?page=admin_tutors" class="form-container" style="max-width: 800px;">
                <input type="hidden" name="tutor_id" value="<?php echo $tutor_id; ?>">
                <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">

                <div class="form-group">
                    <label for="name">Nama Tutor</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($tutor['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="expertise">Keahlian</label>
                    <input type="text" id="expertise" name="expertise" value="<?php echo htmlspecialchars($tutor['expertise'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="6" required><?php echo htmlspecialchars($tutor['bio'] ?? ''); ?></textarea>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php?page=admin_tutors" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
