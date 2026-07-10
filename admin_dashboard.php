<?php
// admin_dashboard.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Database.php';
require_once 'Security.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

// Vuta oda zote kwa kutumia majina sahihi ya column kutoka kwenye .sql yako
$query = "SELECT a.appointment_id, u.username, s.service_name, s.price, a.appointment_date, a.status 
          FROM appointments a
          JOIN users u ON a.user_id = u.user_id
          JOIN services s ON a.service_id = s.service_id
          ORDER BY a.appointment_id DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon System - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f1f2f6; color: #2f3542; }
        .navbar { background: #ff4757; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logout-btn { background: #2f3542; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h3 { margin-bottom: 20px; color: #ff4757; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eceff1; }
        th { background-color: #f8f9fa; font-weight: 600; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; background: #ffbc00; color: white; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>SALON SYSTEM - ADMIN PANEL</h2>
    <div>
        <span style="margin-right: 15px;">Habari, <strong>Admin (admin@gmail.com)</strong></span>
        <a href="logout.php" class="logout-btn">Ondoka (Logout)</a>
    </div>
</div>

<div class="container">
    <div class="card">
        <h3>Orodha ya Oda Zilizowekwa na Wateja (Proof of Orders)</h3>
        <table>
            <thead>
                <tr>
                    <th>Oda ID</th>
                    <th>Jina la Mteja</th>
                    <th>Huduma Aliyochagua</th>
                    <th>Bei (Tsh)</th>
                    <th>Tarehe ya Oda</th>
                    <th>Hali (Status)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($appointments) > 0): ?>
                    <?php foreach ($appointments as $app): ?>
                        <tr>
                            <td>#<?php echo $app['appointment_id']; ?></td>
                            <td><strong><?php echo htmlspecialchars(trim(Security::decrypt($app['username'] ?? ''))); ?></strong></td>
                            <td><?php echo htmlspecialchars($app['service_name']); ?></td>
                            <td><?php echo number_format(floatval($app['price'])); ?> /=</td>
                            <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                            <td><span class="badge"><?php echo htmlspecialchars($app['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #a4b0be; padding: 30px;">Hakuna oda zilizopo kwenye mfumo kwa sasa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>