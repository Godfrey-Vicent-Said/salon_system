<?php
// dashboard.php
session_start();
require_once 'Service.php';

// Session Management: Linda ukurasa ili mtu asiyeingia asiuone
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$serviceManager = new Service();
$msg = "";

// 1. CREATE: Shughulikia uongezaji wa huduma mpya (Admin tu)[cite: 1]
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $serviceManager->setServiceName($_POST['service_name']);
    $serviceManager->setPrice($_POST['price']);
    $result = $serviceManager->create();
    if ($result === true) {
        $msg = "Huduma imeongezwa kikamilifu!";
    } else {
        $msg = $result;
    }
}

// 2. DELETE: Shughulikia ufutaji wa huduma (Admin tu)[cite: 1]
if (isset($_GET['delete_service'])) {
    $serviceManager->delete($_GET['delete_service']);
    header("Location: dashboard.php");
    exit();
}

// 3. READ & SEARCH: Vuta huduma zote zilizopo
$all_services = $serviceManager->readAll();

// Mfumo wa Search wa Huduma (Search Functionality)[cite: 1]
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
    if (!empty($search_query)) {
        $all_services = array_filter($all_services, function($service) use ($search_query) {
            return stripos($service['service_name'], $search_query) !== false;
        });
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon System - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f2f6; color: #2f3542; }
        .navbar { background: #ff4757; padding: 15px 30px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; background: rgba(0,0,0,0.2); padding: 8px 15px; border-radius: 5px; }
        .container { max-width: 1100px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { color: #ff4757; }
        .welcome-box { background: #eccc68; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold; }
        .grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
        form { background: #f7f9fa; padding: 20px; border-radius: 5px; border: 1px solid #ced6e0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ced6e0; border-radius: 5px; }
        .btn { padding: 10px 15px; background: #2ed573; border: none; color: white; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-danger { background: #ff4757; padding: 5px 10px; color: white; text-decoration: none; border-radius: 3px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ced6e0; }
        th { background: #f1f2f6; }
        .msg { background: #2ed573; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .search-box { display: flex; gap: 10px; margin-bottom: 15px; }
        .search-box input { flex: 1; }
        .search-box button { background: #ff4757; color: white; border: none; padding: 0 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>SALON SYSTEM</h2>
    <div>
        <span>Habari, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</span>
        <a href="logout.php" style="margin-left: 15px; background: #2f3542;">Ondoka (Logout)</a>
    </div>
</div>

<div class="container">
    <div class="welcome-box">
        Karibu Saloon! Na uhudumiwe kama Mfalme!
    </div>

    <?php if(!empty($msg)): ?>
        <div class="msg"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="grid">
        <!-- UPANDE WA KUSHOTO: UTAWALA AU UJUMBE -->
        <div>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <h3>Ongeza Huduma Mpya (Admin Panel)</h3>
                <br>
                <form action="dashboard.php" method="POST">
                    <div class="form-group">
                        <label>Jina la Huduma</label>
                        <input type="text" name="service_name" required placeholder="Mfano: Kunyoa, Kusuka, Kuosha nywele">
                    </div>
                    <div class="form-group">
                        <label>Bei (Tsh)</label>
                        <input type="text" name="price" required placeholder="Mfano: 5000">
                    </div>
                    <button type="submit" name="add_service" class="btn">Hifadhi Huduma</button>
                </form>
            <?php else: ?>
                <h3>Weka Miadi (Customer Panel)</h3>
                <br>
                <div style="background: #f7f9fa; padding: 20px; border-radius: 5px; border: 1px solid #ced6e0;">
                    <p>Habari mteja wetu! Angalia orodha ya huduma zilizopo upande wa kulia kisha chagua huduma unayohitaji pindi utakapofika salon kwetu.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- UPANDE WA KULIA: ORODHA NA SEARCH FUNCTIONALITY -->
        <div>
            <h3>Huduma Zinazotolewa</h3>
            <br>
            
            <!-- Fomu ya Kutafuta (Search Form) -->
            <form action="dashboard.php" method="GET" class="search-box" style="background:none; border:none; padding:0;">
                <input type="text" name="search" value="<?php echo $search_query; ?>" placeholder="Tafuta huduma hapa...">
                <button type="submit">Tafuta</button>
                <?php if(!empty($search_query)): ?>
                    <a href="dashboard.php" style="padding: 10px; background:#ced6e0; text-decoration:none; color:black; border-radius:5px;">Futa</a>
                <?php endif; ?>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Huduma</th>
                        <th>Bei (Tsh)</th>
                        <?php if($_SESSION['role'] === 'admin'): ?> <th>Kitendo</th> <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($all_services) > 0): ?>
                        <?php foreach($all_services as $service): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($service['price']); ?></td>
                                <?php if($_SESSION['role'] === 'admin'): ?>
                                    <td>
                                        <a href="dashboard.php?delete_service=<?php echo $service['service_id']; ?>" class="btn-danger" onclick="return confirm('Una uhakika unataka kufuta?')">Futa</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">Hakuna huduma iliyopatikana.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>