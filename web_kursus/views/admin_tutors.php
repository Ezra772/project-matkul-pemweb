<?php
// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Handle Actions
$message = '';
// Handle POST actions (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $tutor_id = (int)($_POST['tutor_id'] ?? 0);
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $expertise = $_POST['expertise'];

    if ($action === 'create') {
        $stmt = $conn->prepare("INSERT INTO tutors (name, bio, expertise) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $bio, $expertise);
        if ($stmt->execute()) {
            $message = "Tutor berhasil ditambahkan!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } elseif ($action === 'update' && $tutor_id > 0) {
        $stmt = $conn->prepare("UPDATE tutors SET name=?, bio=?, expertise=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $bio, $expertise, $tutor_id);
        if ($stmt->execute()) {
            $message = "Tutor berhasil diperbarui!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    header("Location: index.php?page=admin_tutors&message=" . urlencode($message));
    exit;
}

// Handle GET action (Delete)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $tutor_id = (int)$_GET['id'];
    if ($tutor_id > 0) {
        // Note: You might want to handle what happens to courses assigned to this tutor.
        // For simplicity, we'll just delete the tutor. A better approach might be to set tutor_id to NULL.
        $stmt = $conn->prepare("DELETE FROM tutors WHERE id = ?");
        $stmt->bind_param("i", $tutor_id);
        if ($stmt->execute()) {
            $message = "Tutor berhasil dihapus!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    header("Location: index.php?page=admin_tutors&message=" . urlencode($message));
    exit;
}

// Fetch all tutors
$tutors = $conn->query("SELECT * FROM tutors ORDER BY id DESC");

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
                <a href="index.php?page=admin_users">Kelola User</a>
                <a href="index.php?page=admin_tutors" class="active">Kelola Tutor</a>
            </nav>
        </div>
        
        <div class="admin-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Kelola Tutor</h2>
                <a href="index.php?page=admin_tutor_form" class="btn btn-primary">Tambah Tutor</a>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Keahlian</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tutors->num_rows > 0): ?>
                        <?php while($tutor = $tutors->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $tutor['id']; ?></td>
                            <td><?php echo htmlspecialchars($tutor['name']); ?></td>
                            <td><?php echo htmlspecialchars($tutor['expertise']); ?></td>
                            <td>
                                <a href="index.php?page=admin_tutor_form&id=<?php echo $tutor['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <a href="index.php?page=admin_tutors&action=delete&id=<?php echo $tutor['id']; ?>" 
                                   onclick="return confirm('Menghapus tutor juga akan mempengaruhi kursus yang terkait. Lanjutkan?');" 
                                   class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada data tutor.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
