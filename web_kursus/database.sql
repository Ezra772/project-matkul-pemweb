-- Hapus database jika sudah ada
DROP DATABASE IF EXISTS `db_kursus`;

-- Buat database baru
CREATE DATABASE IF NOT EXISTS `db_kursus`;
USE `db_kursus`;

-- Table users
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table tutors  
CREATE TABLE `tutors` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `bio` TEXT,
  `expertise` VARCHAR(255) NOT NULL
);

-- Table courses
CREATE TABLE `courses` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `tutor_id` INT,
  `price` DECIMAL(10,2) NOT NULL,
  `image_url` VARCHAR(500),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tutor_id) REFERENCES tutors(id)
);

-- Table enrollments (sederhana)
CREATE TABLE `enrollments` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `course_id` INT,
  `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('active','completed') DEFAULT 'active',
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Sample data
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('user1', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO `tutors` (`name`, `bio`, `expertise`) VALUES
('Budi Santoso', 'Pengembang web dengan 5+ tahun pengalaman', 'Web Development'),
('Sari Dewi', 'Data scientist dan AI enthusiast', 'Data Science'),
('Agus Pratama', 'UI/UX designer passionate tentang design system', 'UI/UX Design');

INSERT INTO `courses` (`title`, `description`, `category`, `tutor_id`, `price`, `image_url`) VALUES
('Full-Stack Web Development', 'Belajar membangun website dari frontend sampai backend', 'Web Development', 1, 500000, 'assets/gambar/full_stack.jpeg'),
('Data Science Fundamentals', 'Pengenalan data science dan machine learning', 'Data Science', 2, 450000, 'assets/gambar/data_science.jpg'),
('UI/UX Design Mastery', 'Belajar design thinking dan tools modern', 'UI/UX Design', 3, 400000, 'assets/gambar/user_interface.jpg'),
('Mobile App Development with Kotlin', 'Membangun aplikasi Android modern dengan Kotlin dan Jetpack Compose', 'Web Development', 1, 550000, 'assets/gambar/mobile_app.jpg'),
('Digital Marketing 101', 'Dasar-dasar pemasaran digital, dari SEO hingga media sosial', 'UI/UX Design', 3, 350000, 'assets/gambar/digital_marketing.avif');

