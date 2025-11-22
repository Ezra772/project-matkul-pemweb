<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-light), var(--bg-white));">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-dark);">Login ke EduNext</h2>
        
        <?php
        include 'controllers/auth.php';
        // Handle login form
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $result = loginUser($conn, $email, $password);
            
            if ($result == 'success') {
                header('Location: index.php?page=' . ($_SESSION['role'] == 'admin' ? 'admin' : 'dashboard'));
                exit;
            } else {
                echo '<div style="background: #fee; color: #c33; padding: 1rem; border-radius: var(--radius); margin-bottom: 1rem;">' . $result . '</div>';
            }
        }
        ?>
        
        <form method="POST">
            <input type="hidden" name="login" value="1">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem;">
            Belum punya akun? <a href="index.php?page=register">Daftar di sini</a>
        </p>
    </div>
</div>