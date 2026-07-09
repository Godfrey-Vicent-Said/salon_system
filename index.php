<?php
// index.php
require_once 'User.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    // Inasoma kutoka kwenye input mpya inayoitwa 'login_identity'
    $user->setEmail($_POST['login_identity']);
    $user->setPassword($_POST['password']);
    
    $login_result = $user->login();
    
    if ($login_result === true) {
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $message = $login_result;
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon System - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?q=80&w=1920') no-repeat center center fixed; background-size: cover; height: 100vh; display: flex; justify-content: center; align-items: center; }
        body::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.6); z-index: 1; }
        .login-container { position: relative; z-index: 2; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); padding: 40px; border-radius: 15px; width: 400px; box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; }
        h2 { text-align: center; margin-bottom: 30px; font-weight: 600; letter-spacing: 1px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; }
        input { width: 100%; padding: 12px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; outline: none; transition: 0.3s; }
        input:focus { border-color: #fff; background: rgba(255, 255, 255, 0.2); }
        .btn { width: 100%; padding: 12px; background: #ff4757; border: none; border-radius: 8px; color: white; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn:hover { background: #e84118; }
        .error-msg { background: rgba(255, 71, 87, 0.3); padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; font-size: 14px; border: 1px solid #ff4757; }
        p { text-align: center; margin-top: 20px; font-size: 14px; }
        a { color: #ff4757; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>SALON SYSTEM</h2>
    
    <?php if(!empty($message)): ?>
        <div class="error-msg"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="index.php?v=2" method="POST">
        <div class="form-group">
            <label>Barua Pepe / Username</label>
            <input type="text" name="login_identity" required placeholder="Weka email au username">
        </div>
        <div class="form-group">
            <label>Nenosiri (Password)</label>
            <input type="password" name="password" required placeholder="Weka nenosiri yako">
        </div>
        <button type="submit" class="btn">Ingia Mfumoni</button>
    </form>
    
    <p>Huna akaunti? <a href="register.php">Jisajili hapa</a></p>
</div>

</body>
</html>