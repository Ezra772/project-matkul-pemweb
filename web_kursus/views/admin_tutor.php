<?php
// Check if admin
if ($_SESSION['role'] != 'admin') {
    header('Location: index.php');
    exit;
}

// Handle add tutor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tutor'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $expertise = $_POST['expertise'];
    
    $conn->query("INSERT INTO tutors (name, bio, expertise) VALUES ('$name', '$bio', '$expertise')");
    
    $success_message = "Tutor berhasil ditambahkan!";
}
?>

<div class="admin-panel">
    <h1 style="margin-bottom: 2rem; color: var(--text-dark);">Kelola Tutor</h1>
    
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
            <?php if (isset($success_message)): ?>
                <div style="background: #efe; color: #363; padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Form Tambah Tutor -->
                <div>
                    <h3 style="margin-bottom: 1rem;">Tambah Tutor Baru</h3>
                    <form method="POST" style="background: var(--bg-light); padding: 1.5rem; border-radius: var(--radius);">
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Tutor</label>
                            <input type="text" name="name" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bidang Keahlian</label>
                            <select name="expertise" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius);">
                                <option value="Web Development">Web Development</option>
                                <option value="Data Science">Data Science</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                                <option value="Mobile Development">Mobile Development</option>
                                <option value="Cyber Security">Cyber Security</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bio</label>
                            <textarea name="bio" required style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: var(--radius); height: 100px;"></textarea>
                        </div>
                        
                        <button type="submit" name="add_tutor" class="btn btn-primary">Tambah Tutor</button>
                    </form>
                </div>
                
                <!-- Daftar Tutor -->
                <div>
                    <h3 style="margin-bottom: 1rem;">Daftar Tutor</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Keahlian</th>
                                <th>Bio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tutors = $conn->query("SELECT * FROM tutors");
                            while ($tutor = $tutors->fetch_assoc()):
                            ?>
                            <tr>
                                <td><strong><?php echo $tutor['name']; ?></strong></td>
                                <td><?php echo $tutor['expertise']; ?></td>
                                <td><?php echo substr($tutor['bio'], 0, 50) . '...'; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>