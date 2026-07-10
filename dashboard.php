<?php
// dashboard.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kuzuia mtu asiyeingia akaunti kuona hii page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

require_once 'Service.php';
$serviceObj = new Service();
$services = $serviceObj->readAll();
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon System - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f1f2f6; color: #333; }
        .navbar { background: #ff4757; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .navbar h2 { font-size: 20px; font-weight: 600; }
        .logout-btn { background: #2f3542; color: white; padding: 8px 18px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: 0.3s; }
        .logout-btn:hover { background: #1e222b; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        .welcome-box { grid-column: 1 / -1; background: #ecc94b; color: #1a202c; padding: 20px; border-radius: 8px; font-weight: bold; font-size: 18px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e1e4e8; }
        .card h3 { margin-bottom: 20px; border-bottom: 2px solid #ff4757; padding-bottom: 10px; color: #2f3542; }
        .info-panel p { font-size: 15px; line-height: 1.6; color: #57606f; }
        .search-container { display: flex; gap: 10px; margin-bottom: 20px; }
        .search-container input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; outline: none; }
        .search-container button { background: #ff4757; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #2f3542; font-weight: 600; }
        .book-btn { background: #2ed573; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 13px; transition: 0.3s; display: inline-block; }
        .book-btn:hover { background: #26af5f; box-shadow: 0 2px 8px rgba(46,213,115,0.4); }
    </style>
</head>
<body>

<div class="navbar">
    <h2>SALON SYSTEM</h2>
    <div>
        <span style="margin-right: 15px;">Habari, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (customer)</span>
        <a href="logout.php" class="logout-btn">Ondoka (Logout)</a>
    </div>
</div>

<div class="container">
    <div class="welcome-box">Karibu Saloon! Na uhudumiwe kama Mfalme!</div>
    
    <div class="card">
        <h3>Weka Miadi (Customer Panel)</h3>
        <div class="info-panel">
            <p>Habari mteja wetu! Angalia orodha ya huduma zilizopo upande wa kulia kisha chagua huduma unayohitaji kwa kubofya kitufe cha <strong>"Weka Miadi"</strong> ili kuweka oda yako moja kwa moja kwenda kwa utawala wetu.</p>
        </div>
    </div>
    
    <div class="card">
        <h3>Huduma Zinazotolewa</h3>
        <div class="search-container">
            <input type="text" placeholder="Tafuta huduma hapa...">
            <button>Tafuta</button>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Huduma</th>
                    <th>Bei (Tsh)</th>
                    <th>Hatua</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($services)): ?>
                    <?php foreach($services as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo number_format($service['price']); ?></td>
                            <td>
                                <a href="book_service.php?service_id=<?php echo $service['service_id']; ?>" class="book-btn" onclick="return confirm('Je, una uhakika unataka kuweka miadi ya huduma hii?');">Weka Miadi</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #aaa;">Hakuna huduma zilizopatikana.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>