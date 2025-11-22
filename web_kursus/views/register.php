<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-light), var(--bg-white));">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-dark);">Daftar EduNext</h2>
        
        <?php
        include 'controllers/auth.php';
        // Handle registration
        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $result = registerUser($conn, $username, $email, $password);
            
            if ($result == 'success') {
                echo '<div style="background: #efe; color: #363; padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">
                    Registrasi berhasil! <a href="index.php?page=login">Login di sini</a>
                </div>';
            } else {
                echo '<div style="background: #fee; color: #c33; padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">' . $result . '</div>';
            }
        }
        ?>
        
        <form method="POST">
            <input type="hidden" name="register" value="1">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem;">
            Sudah punya akun? <a href="index.php?page=login">Login di sini</a>
        </p>
    </div>
</div>