<?php
// admin_dashboard.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ulinzi: Kama sio admin, mfukuze arudi kwenye login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon System - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f1f2f6; color: #2f3542; }
        .navbar { background: #ff4757; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .logout-btn { background: #2f3542; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 20px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2, h3 { margin-bottom: 20px; color: #2f3542; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; }
        .btn-submit { background: #2ed573; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eceff1; }
        th { background-color: #f8f9fa; color: #57606f; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; background: #ffbc00; color: white; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>SALON MANAGEMENT SYSTEM (ADMIN)</h2>
    <div>
        <span>Habari, <strong><?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)</strong></span>
        <a href="logout.php" class="logout-btn">Ondoka (Logout)</a>
    </div>
</div>

<div class="container">
    <div class="grid">
        <div class="card">
            <h3>Ongeza Huduma Mpya</h3>
            <form action="process_service.php" method="POST">
                <div class="form-group">
                    <label>Jina la Huduma</label>
                    <input type="text" name="service_name" required placeholder="Mfano: Kusuka">
                </div>
                <div class="form-group">
                    <label>Bei (Tsh)</label>
                    <input type="number" name="price" required placeholder="Mfano: 15000">
                </div>
                <button type="submit" class="btn-submit">Hifadhi Huduma</button>
            </form>
        </div>

        <div class="card">
            <h3>Orodha ya Miadi (Appointments)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Mteja</th>
                        <th>Huduma</th>
                        <th>Hali (Status)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>frey</td>
                        <td>Kunyoa Kawaida</td>
                        <td><span class="badge">Inasubiri</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>