<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$category_icons = [
    'Makeup' => '',
    'Skincare' => '',
    'Hair Care' => '',
    'Body Care' => '',
    'Nails' => ''
];

$categories = ['Makeup', 'Skincare', 'Hair Care', 'Body Care', 'Nails'];
$totals = [];
$budgets = [];


foreach ($categories as $category) {
    $stmt = $pdo->prepare("SELECT SUM(price) as total FROM purchases WHERE user_id = ? AND category = ?");
    try {
        $stmt->execute([$user_id, $category]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totals[$category] = $row['total'] ?? 0;
    } catch (PDOException $e) {
        $totals[$category] = 0;
    }
    

    $stmt = $pdo->prepare("SELECT budget_limit FROM budgets WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $category]);
    $budget_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $budgets[$category] = $budget_row['budget_limit'] ?? null;
}



$expense_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $item_name = $_POST['item_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $purchase_date = $_POST['purchase_date'] ?? date('Y-m-d');

    if ($item_name && $category && $price) {
        $stmt = $pdo->prepare("INSERT INTO purchases (user_id, item_name, category, price, purchase_date) VALUES (?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$user_id, $item_name, $category, $price, $purchase_date]);
            $expense_message = "Expense added successfully!";
            
            foreach ($categories as $category_name) {
                $stmt = $pdo->prepare("SELECT SUM(price) as total FROM purchases WHERE user_id = ? AND category = ?");
                $stmt->execute([$user_id, $category_name]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $totals[$category_name] = $row['total'] ?? 0;
            }
        } catch (PDOException $e) {
            $expense_message = "Error adding expense: " . $e->getMessage();
        }
    } else {
        $expense_message = "Please fill in all fields!";
    }
}

$budget_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_budget'])) {
    $category = $_POST['budget_category'] ?? '';
    $budget_limit = $_POST['budget_limit'] ?? '';

    if ($category && $budget_limit) {
        $stmt = $pdo->prepare("INSERT INTO budgets (user_id, category, budget_limit) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE budget_limit = ?");
        try {
            $stmt->execute([$user_id, $category, $budget_limit, $budget_limit]);
            $budget_message = "Budget updated successfully!";
            
            $stmt = $pdo->prepare("SELECT budget_limit FROM budgets WHERE user_id = ? AND category = ?");
            $stmt->execute([$user_id, $category]);
            $budget_row = $stmt->fetch(PDO::FETCH_ASSOC);
            $budgets[$category] = $budget_row['budget_limit'] ?? null;
        } catch (PDOException $e) {
            $budget_message = "Error setting budget: " . $e->getMessage();
        }
    } else {
        $budget_message = "Please fill in all fields!";
    }
}



try {
    $stmt = $pdo->prepare("SELECT id, item_name, category, price, purchase_date FROM purchases WHERE user_id = ? ORDER BY purchase_date DESC");
    $stmt->execute([$user_id]);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $expenses = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            margin: 0; 
            background: linear-gradient(135deg, #ffb3d9 0%, #ffe6f0 100%);
            min-height: 100vh;
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
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        h2 { color: #ff3399; font-size: 32px; margin-bottom: 10px; }
        .subtitle { color: #666; font-size: 16px; margin-bottom: 30px; }
        h3 { color: #ff3399; font-size: 22px; margin-top: 40px; margin-bottom: 20px; }
        
        .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .summary-card { 
            background: white; 
            padding: 20px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            animation: slideUp 0.5s ease;
        }
        .summary-card .icon { font-size: 32px; margin-bottom: 10px; }
        .summary-card .label { color: #999; font-size: 13px; margin-bottom: 8px; }
        .summary-card .value { color: #ff3399; font-size: 28px; font-weight: 700; }
        
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .card { 
            background-color: white; 
            padding: 24px; 
            border-radius: 15px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.1); 
            text-align: center; 
            transition: all 0.3s ease;
            border-left: 4px solid #ddd;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #ff3399, #ff69b4);
        }
        .card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(255, 51, 153, 0.2); }
        .card .icon { font-size: 40px; margin-bottom: 10px; }
        .card h3 { margin: 0 0 15px 0; color: #ff3399; font-size: 18px; }
        .card p { font-size: 28px; font-weight: 700; color: #333; margin: 0 0 10px 0; }
        .card .spent { font-size: 12px; color: #999; margin-top: 5px; }
        .card .budget-info { font-size: 12px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; }
        .card .remaining { font-weight: 600; margin-top: 8px; font-size: 14px; }
        .card.warning { border-left-color: #ff9800; }
        .card.danger { border-left-color: #f44336; }
        .progress-bar { 
            width: 100%; 
            height: 6px; 
            background: #f0f0f0; 
            border-radius: 3px; 
            overflow: hidden; 
            margin-top: 10px;
        }
        .progress-fill { height: 100%; border-radius: 3px; transition: width 0.3s ease; }
        
        .chart-container { 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            animation: slideUp 0.6s ease;
        }
        .chart-container h3 { margin-top: 0; }
        
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        th, td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: linear-gradient(135deg, #ff3399 0%, #ff1493 100%); color: white; font-weight: 600; }
        tr:hover { background-color: #ffe6f0; }
        .action-btn { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 6px; color: white; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s; }
        .edit-btn { background-color: #4CAF50; } .delete-btn { background-color: #f44336; }
        .edit-btn:hover { background-color: #45a049; transform: scale(1.05); } .delete-btn:hover { background-color: #da190b; transform: scale(1.05); }
        
        .add-user-form { 
            background: white; 
            padding: 24px; 
            border-radius: 15px; 
            margin-bottom: 30px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            animation: slideUp 0.5s ease;
        }
        .add-user-form input, .add-user-form select, .add-user-form textarea { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border-radius: 8px; 
            border: 2px solid #eee;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }
        .add-user-form input:focus, .add-user-form select:focus, .add-user-form textarea:focus { 
            outline: none; 
            border-color: #ff3399;
            box-shadow: 0 0 10px rgba(255, 51, 153, 0.2);
        }
        .add-user-form button { 
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
        .add-user-form button:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(255, 51, 153, 0.3); }
        
        .message { margin-bottom: 15px; padding: 14px; border-radius: 8px; font-weight: 500; }
        .message.success { color: #2e7d32; background-color: #c8e6c9; border-left: 4px solid #2e7d32; }
        .message.error { color: #c62828; background-color: #ffcdd2; border-left: 4px solid #c62828; }
        
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
            .card { flex: 1 1 100%; }
            nav { flex-direction: column; gap: 15px; }
            nav div { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <nav>
        <h1>Makeup Store Dashboard</h1>
        <div>
            <a href="profile.php">👤 My Profile</a>
            <a href="wishlist.php" style="margin-right: 20px;">My Wishlist</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
        <p>Here’s a summary of your expenses by category:</p>

        <div class="cards">
            <?php foreach ($totals as $category => $total): 
                $budget = $budgets[$category];
                $remaining = $budget ? $budget - $total : null;
                $percentage = $budget ? ($total / $budget) * 100 : 0;
                $card_class = '';
                if ($budget && $percentage >= 90) $card_class = ' danger';
                elseif ($budget && $percentage >= 75) $card_class = ' warning';
                $icon = $category_icons[$category] ?? '';
            ?>
                <div class="card<?= $card_class ?>">
                    <div class="icon"><?= $icon ?></div>
                    <h3><?= htmlspecialchars($category) ?></h3>
                    <p>$<?= number_format($total, 2) ?></p>
                    <div class="spent">Spent</div>
                    <?php if ($budget): ?>
                        <div class="budget-info">
                            <div style="font-size: 12px; color: #999;">Budget: $<?= number_format($budget, 2) ?></div>
                            <div class="remaining" style="color: <?= $remaining < 0 ? '#f44336' : '#2e7d32' ?>;">
                                Remaining: $<?= number_format($remaining, 2) ?>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= min($percentage, 100) ?>%; background: <?= $percentage >= 90 ? '#f44336' : ($percentage >= 75 ? '#ff9800' : '#4CAF50') ?>;"></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="budget-info">
                            <div style="color: #999; font-size: 12px;">No budget set</div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php
 
        $chart_labels = json_encode(array_keys($totals));
        $chart_data = json_encode(array_values($totals));
        $total_spent = array_sum($totals);
        ?>

        <?php if ($total_spent > 0): ?>
        <div class="chart-container">
            <h3>💹 Spending Distribution</h3>
            <canvas id="spendingChart"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('spendingChart').getContext('2d');
            const spendingChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?= $chart_labels ?>,
                    datasets: [{
                        data: <?= $chart_data ?>,
                        backgroundColor: [
                            '#FF69B4',
                            '#FFB6C1',
                            '#FFC0CB',
                            '#FFE4E1',
                            '#FFF0F5'
                        ],
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: "'Poppins', sans-serif", size: 13 },
                                padding: 15,
                                color: '#666'
                            }
                        }
                    }
                }
            });
        </script>
        <?php endif; ?>

        <h3> Set Budget Limits</h3>
        <div class="add-user-form">
            <?php if ($budget_message) echo "<div class='message " . (strpos($budget_message, 'Error') ? 'error' : 'success') . "'>" . htmlspecialchars($budget_message) . "</div>"; ?>
            <form method="post" action="">
                <input type="hidden" name="set_budget" value="1">
                <select name="budget_category" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>"><?= $cat ?> (Currently: $<?= number_format($totals[$cat], 2) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="budget_limit" placeholder="Budget Limit ($)" step="0.01" min="0" required>
                <button type="submit">Set Budget</button>
            </form>
        </div>

        <h3> Add New Expense</h3>
        <div class="add-user-form">
            <?php if ($expense_message) echo "<div class='message " . (strpos($expense_message, 'Error') ? 'error' : 'success') . "'>" . htmlspecialchars($expense_message) . "</div>"; ?>
            <form method="post" action="">
                <input type="hidden" name="add_expense" value="1">
                <input type="text" name="item_name" placeholder="Item Name" required>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>"><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="price" placeholder="Price" step="0.01" required>
                <input type="date" name="purchase_date" value="<?= date('Y-m-d') ?>" required>
                <button type="submit">Add Expense</button>
            </form>
        </div>

        <h3> Your Expenses</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th><th>Item Name</th><th>Category</th><th>Price</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expenses)): ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($expense['purchase_date'])) ?></td>
                            <td><?= htmlspecialchars($expense['item_name']) ?></td>
                            <td><?= htmlspecialchars($expense['category']) ?></td>
                            <td>$<?= number_format($expense['price'], 2) ?></td>
                            <td>
                                <a href="edit_expense.php?id=<?= $expense['id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="delete_expense.php?id=<?= $expense['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No expenses found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>


    </div>
</body>
</html>
