<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {
    $title = htmlspecialchars($_POST['title'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $tech_stack = htmlspecialchars($_POST['tech_stack'] ?? '');
    $link = htmlspecialchars($_POST['project_link'] ?? '');

    $sql = "INSERT INTO projects (title, description, tech_stack, project_link) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $tech_stack, $link);

    if ($stmt->execute()) {
        $message = "The project has been successfully added";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "The project has been deleted.";
    }
    $stmt->close();
}

$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Umut Topal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .admin-section { padding-top: 120px; max-width: 1000px; margin: 0 auto; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .admin-card { background: #1a1f2e; padding: 25px; border-radius: 10px; border: 1px solid rgba(42, 252, 133, 0.1); }
        .admin-card h3 { color: #2afc85; margin-bottom: 20px; }
        .project-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .delete-btn { color: #ff4d4d; text-decoration: none; font-size: 0.9rem; }
        .msg { background: rgba(42, 252, 133, 0.1); color: #2afc85; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <header>
        <a href="index.html" class="logo">
            <i class="fas fa-code logo-icon"></i>
            <span class="logo-text">Admin Panel</span>
        </a>
        <nav>
            <ul>
                <li><a href="index.html">Back to Site</a></li>
                <li><a href="logout.php" style="color: #ff4d4d;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="admin-section">
        <h2 class="section-title">Welcome, Umut</h2>
        
        <?php if($message): ?>
            <div class="msg"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <div class="admin-card">
                <h3><i class="fas fa-plus-circle"></i> Add a New Project</h3>
                <form method="POST" class="contact-form" style="padding: 0; background: transparent;">
                    <div class="form-group">
                        <input type="text" name="title" placeholder="Project Name" required>
                    </div>
                    <div class="form-group">
                        <textarea name="description" placeholder="Project Description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" name="tech_stack" placeholder="Technologies Used (e.g., ESP32, C++)" required>
                    </div>
                    <div class="form-group">
                        <input type="url" name="project_link" placeholder="Project Link (e.g., GitHub)">
                    </div>
                    <button type="submit" name="add_project" class="btn btn-primary" style="width: 100%;">Ekle</button>
                </form>
            </div>

            <div class="admin-card">
                <h3><i class="fas fa-tasks"></i> Current Projects</h3>
                <div class="project-list">
                    <?php while($row = $projects->fetch_assoc()): ?>
                        <div class="project-item">
                            <div>
                                <strong><?php echo $row['title']; ?></strong>
                                <p style="font-size: 0.8rem; color: #888;"><?php echo $row['tech_stack']; ?></p>
                            </div>
                            <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Emin misin?')">
                                <i class="fas fa-trash"></i> Sil
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>