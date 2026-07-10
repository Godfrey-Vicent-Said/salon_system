<?php
// dashboard.php
session_start();
require_once 'Service.php';

// Mteja tu ndiye anayeruhusiwa hapa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$serviceManager = new Service();
$all_services = $serviceManager->readAll();

$msg = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'booked') {
        $msg = "Miadi yako imewekwa kikamilifu na imetumwa kwa Admin kwa ajili ya uhakiki!";
    } elseif ($_GET['status'] === 'error') {
        $msg = "Hitilafu imetokea wakati wa kuweka miadi. Tafadhali jaribu tena.";
    }
}

// Mfumo wa Search wa Huduma
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
    <title>Salon System - Customer Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f2f6; color: #2f3542; }
        .navbar { background: #ff4757; padding: 15px 30px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; background: rgba(0,0,0,0.2); padding: 8px 15px; border-radius: 5px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h3 { color: #ff4757; margin-bottom: 15px; }
        .welcome-box { background: #eccc68; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold; }
        .grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .info-panel { background: #f7f9fa; padding: 20px; border-radius: 5px; border: 1px solid #ced6e0; }
        .btn-book { background: #2ed573; padding: 6px 15px; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 13px; display: inline-block; transition: 0.3s; }
        .btn-book:hover { background: #26af5f; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ced6e0; font-size: 14px; }
        th { background: #f1f2f6; }
        .msg { background: #2ed573; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; }
        .search-box { display: flex; gap: 10px; margin-bottom: 15px; }
        .search-box input { flex: 1; padding: 10px; border: 1px solid #ced6e0; border-radius: 5px; }
        .search-box button { background: #ff4757; color: white; border: none; padding: 0 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>SALON SYSTEM (CUSTOMER PANEL)</h2>
    <div>
        <span>Habari mteja wetu, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
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
        <div class="info-panel">
            <h3>Weka Miadi Hapa</h3>
            <p style="line-height: 1.6; margin-bottom: 10px;">Chagua huduma unayoitaka kutoka kwenye orodha iliyopo upande wa kulia.</p>
            <p style="line-height: 1.6;">Baada ya kubofya kitufe cha <strong>"Chagua / Book"</strong>, oda yako itasajiliwa kiotomatiki kwenye database yetu kwa ajili ya ushahidi.</p>
        </div>

        <div>
            <h3>Huduma Zinazotolewa</h3>
            
            <form action="dashboard.php" method="GET" class="search-box">
                <input type="text" name="search" value="<?php echo $search_query; ?>" placeholder="Tafuta huduma hapa...">
                <button type="submit">Tafuta</button>
                <?php if(!empty($search_query)): ?>
                    <a href="dashboard.php" style="padding: 10px; background:#ced6e0; text-decoration:none; color:black; border-radius:5px; font-size:14px; font-weight:bold;">Futa</a>
                <?php endif; ?>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Jina la Huduma</th>
                        <th>Bei (Tsh)</th>
                        <th>Kitendo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($all_services) > 0): ?>
                        <?php foreach($all_services as $service): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($service['service_name']); ?></strong></td>
                                <td><?php echo number_format($service['price']); ?> /=</td>
                                <td>
                                    <a href="book_service.php?service_id=<?php echo $service['service_id']; ?>" class="btn-book" onclick="return confirm('Je, una uhakika unataka kuchagua huduma hii?')">Chagua / Book</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px; color: #a4b0be;">Hakuna huduma iliyopatikana.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>