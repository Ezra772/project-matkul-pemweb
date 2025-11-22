<?php
// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$user_id = (int)($_GET['id'] ?? 0);
if ($user_id === 0) {
    echo "<p>User ID tidak valid.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<p>Pengguna tidak ditemukan.</p>";
    exit;
}
?>

<div class="admin-panel">
    <div class="admin-grid">
        <div class="admin-sidebar">
            <nav>
                <a href="index.php?page=admin">Dashboard</a>
                <a href="index.php?page=admin_courses">Kelola Kursus</a>
                <a href="index.php?page=admin_users" class="active">Kelola User</a>
                <a href="index.php?page=admin_tutors">Kelola Tutor</a>
            </nav>
        </div>
        
        <div class="admin-content">
            <h2 style="margin-bottom: 2rem;">Edit Role Pengguna</h2>
            
            <form method="POST" action="index.php?page=admin_users" class="form-container" style="max-width: 600px;">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <input type="hidden" name="action" value="update">

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : '';?>>Simpan</button>
                    <a href="index.php?page=admin_users" class="btn btn-secondary">Batal</a>
                </div>
                 <?php if ($user['id'] == $_SESSION['user_id']): ?>
                    <p style="color: var(--text-light); margin-top: 1rem;">Anda tidak dapat mengubah role akun Anda sendiri.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
