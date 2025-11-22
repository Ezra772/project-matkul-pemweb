<?php
// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$message = '';

// Handle POST action (Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $user_id = (int)$_POST['user_id'];
    $role = $_POST['role'];

    // Ensure role is either 'admin' or 'user' to prevent invalid data
    if ($role === 'admin' || $role === 'user') {
        if ($user_id > 0) {
            $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
            $stmt->bind_param("si", $role, $user_id);
            if ($stmt->execute()) {
                $message = "Role pengguna berhasil diperbarui!";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
    } else {
        $message = "Error: Role tidak valid.";
    }
    header("Location: index.php?page=admin_users&message=" . urlencode($message));
    exit;
}

// Handle GET action (Delete)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    
    // Prevent admin from deleting themselves
    if ($user_id > 0 && $user_id != $_SESSION['user_id']) {
        // Delete related enrollments first
        $delete_enrollments_stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = ?");
        $delete_enrollments_stmt->bind_param("i", $user_id);
        $delete_enrollments_stmt->execute();

        // Then delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $message = "Pengguna berhasil dihapus!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "Error: Anda tidak dapat menghapus akun Anda sendiri.";
    }
    header("Location: index.php?page=admin_users&message=" . urlencode($message));
    exit;
}

// Fetch all users
$users = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id DESC");

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
                <a href="index.php?page=admin_courses">Kelola Kursus</a>
                <a href="index.php?page=admin_users" class="active">Kelola User</a>
                <a href="index.php?page=admin_tutors">Kelola Tutor</a>
            </nav>
        </div>
        
        <div class="admin-content">
            <h2 style="margin-bottom: 2rem;">Kelola Pengguna</h2>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->num_rows > 0): ?>
                        <?php while($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span style="background: <?php echo $user['role'] == 'admin' ? 'var(--primary)' : 'var(--secondary)'; ?>; 
                                          color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <a href="index.php?page=admin_user_form&id=<?php echo $user['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <?php if ($user['id'] != $_SESSION['user_id']): // Prevent deleting self ?>
                                    <a href="index.php?page=admin_users&action=delete&id=<?php echo $user['id']; ?>" 
                                       onclick="return confirm('Anda yakin ingin menghapus pengguna ini? Semua data pendaftaran kursus yang terkait juga akan dihapus.');" 
                                       class="btn btn-danger btn-sm">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Belum ada data pengguna.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
