<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$profile_message = '';
$message_type = '';


$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$name || !$email) {
        $profile_message = "Name and Email are required!";
        $message_type = 'error';
    } elseif ($password && $password !== $password_confirm) {
        $profile_message = "Passwords do not match!";
        $message_type = 'error';
    } elseif ($password && strlen($password) < 6) {
        $profile_message = "Password must be at least 6 characters!";
        $message_type = 'error';
    } else {
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $profile_message = "Email already in use by another account!";
            $message_type = 'error';
        } else {
            try {
                if (!empty($password)) {
                   
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $hashed_password, $user_id]);
                } else {
                    
                    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $user_id]);
                }
                
                
                $_SESSION['user_name'] = $name;
                
                $profile_message = "Profile updated successfully! ✨";
                $message_type = 'success';
                
              
                $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $profile_message = "Error updating profile: " . $e->getMessage();
                $message_type = 'error';
            }
        }
    }
}


$stmt = $pdo->prepare("SELECT COUNT(*) as total_expenses, SUM(price) as total_spent FROM purchases WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #ffb3d9 0%, #ffe6f0 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        nav {
            background: linear-gradient(135deg, #ff3399 0%, #ff1493 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(255, 51, 153, 0.3);
        }
        nav h1 { margin: 0; font-size: 28px; font-weight: 700; }
        nav a { color: white; text-decoration: none; font-weight: 600; margin-left: 20px; transition: opacity 0.3s; }
        nav a:hover { opacity: 0.8; }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .profile-header {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 30px;
            animation: slideUp 0.5s ease;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ff3399 0%, #ff1493 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            margin: 0 auto 20px;
        }

        .profile-header h2 {
            margin: 0 0 10px 0;
            color: #ff3399;
            font-size: 28px;
        }

        .profile-header p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 25px;
        }

        .stat-item {
            background: #fff5f9;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-item .label {
            color: #999;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .stat-item .value {
            color: #ff3399;
            font-size: 24px;
            font-weight: 700;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            animation: slideUp 0.6s ease;
        }

        .form-container h3 {
            color: #ff3399;
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff3399;
            box-shadow: 0 0 10px rgba(255, 51, 153, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .divider {
            border: none;
            border-top: 2px solid #eee;
            margin: 25px 0;
        }

        .password-note {
            background: #fff5f9;
            border-left: 4px solid #ff3399;
            padding: 12px;
            border-radius: 5px;
            color: #666;
            font-size: 12px;
            margin-bottom: 15px;
        }

        button {
            background: linear-gradient(135deg, #ff3399 0%, #ff1493 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            font-size: 16px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(255, 51, 153, 0.3);
        }

        .message {
            margin-bottom: 20px;
            padding: 14px;
            border-radius: 8px;
            font-weight: 500;
        }

        .message.success {
            color: #2e7d32;
            background-color: #c8e6c9;
            border-left: 4px solid #2e7d32;
        }

        .message.error {
            color: #c62828;
            background-color: #ffcdd2;
            border-left: 4px solid #c62828;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            color: #ff3399;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        .back-link:hover {
            opacity: 0.7;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 700px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav>
        <h1>👤 My Profile</h1>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
       
        <div class="profile-header">
            <div class="profile-avatar">👩‍🦰</div>
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <p><?= htmlspecialchars($user['email']) ?></p>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="label">Total Expenses</div>
                    <div class="value"><?= $stats['total_expenses'] ?></div>
                </div>
                <div class="stat-item">
                    <div class="label">Total Spent</div>
                    <div class="value">$<?= number_format($stats['total_spent'] ?? 0, 2) ?></div>
                </div>
            </div>
        </div>

        
        <div class="form-container">
            <h3>✏️ Edit Profile</h3>
            
            <?php if ($profile_message): ?>
                <div class="message <?= $message_type ?>">
                    <?= htmlspecialchars($profile_message) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <hr class="divider">

                <div class="password-note">
                    💡 Leave password fields empty to keep your current password
                </div>

                <div class="form-group">
                    <label for="password">New Password (Optional)</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password or leave blank">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm new password">
                </div>

                <button type="submit">💾 Save Changes</button>
            </form>

            <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
