<?php
// Check if admin
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}
?>

<div class="admin-panel">
    <h1 style="margin-bottom: 2rem; color: var(--text-dark);">Admin Panel</h1>
    
    <div class="admin-grid">
        <div class="admin-sidebar">
            <nav>
                <a href="index.php?page=admin" class="active">Dashboard</a>
                <a href="index.php?page=admin_courses">Kelola Kursus</a>
                <a href="index.php?page=admin_users">Kelola User</a>
                <a href="index.php?page=admin_tutors">Kelola Tutor</a>
            </nav>
        </div>
        
        <div class="admin-content">
            <h2 style="margin-bottom: 1.5rem;">Statistik Sistem</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                <div style="background: var(--primary-light); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                    <h3 style="color: var(--primary-dark);">Total Kursus</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark);">
                        <?php echo $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0]; ?>
                    </p>
                </div>
                
                <div style="background: var(--primary-light); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                    <h3 style="color: var(--primary-dark);">Total User</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark);">
                        <?php echo $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0]; ?>
                    </p>
                </div>
                
                <div style="background: var(--primary-light); padding: 1.5rem; border-radius: var(--radius); text-align: center;">
                    <h3 style="color: var(--primary-dark);">Total Tutor</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: var(--text-dark);">
                        <?php echo $conn->query("SELECT COUNT(*) FROM tutors")->fetch_row()[0]; ?>
                    </p>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <h3 style="margin-bottom: 1rem;">Kursus Terbaru</h3>
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
                            $courses = $conn->query("SELECT * FROM courses ORDER BY id DESC LIMIT 5");
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
                
                <div>
                    <h3 style="margin-bottom: 1rem;">User Terbaru</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 5");
                            while ($user = $users->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span style="background: <?php echo $user['role'] == 'admin' ? 'var(--primary)' : 'var(--secondary)'; ?>; 
                                              color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>