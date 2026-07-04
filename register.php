<?php
// register.php
require_once 'User.php';

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $user->setUsername($_POST['username']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);
    $user->setRole('customer'); // Role ya kawaida kwa anayejisajili mtandaoni
    
    $register_result = $user->register();
    
    if ($register_result === true) {
        $message = "Usajili umefanikiwa! Sasa unaweza kuingia.";
        $success = true;
    } else {
        $message = $register_result;
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon System - Register</title>
    <style>
        /* CSS ni ile ile kama ya index.php ili kuleta muonekano unaoendana */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            background: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?q=80&w=1920') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        body::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6); z-index: 1;
        }
        .register-container {
            position: relative; z-index: 2;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 40px; border-radius: 15px; width: 400px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2); color: #fff;
        }
        h2 { text-align: center; margin-bottom: 25px; font-weight: 600; letter-spacing: 1px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-size: 14px; }
        input {
            width: 100%; padding: 12px; border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; outline: none;
        }
        input:focus { background: rgba(255, 255, 255, 0.2); border-color: #fff; }
        .btn {
            width: 100%; padding: 12px; background: #2ed573; border: none; border-radius: 8px;
            color: white; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px;
        }
        .btn:hover { background: #26af5f; }
        .msg {
            padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; font-size: 14px;
        }
        .error-msg { background: rgba(255, 71, 87, 0.3); border: 1px solid #ff4757; }
        .success-msg { background: rgba(46, 213, 115, 0.3); border: 1px solid #2ed573; }
        p { text-align: center; margin-top: 20px; font-size: 14px; }
        a { color: #2ed573; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-container">
    <h2>JISAJILI HAPA</h2>
    
    <?php if(!empty($message)): ?>
        <div class="msg <?php echo $success ? 'success-msg' : 'error-msg'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label>Jina la Mtumiaji (Username)</label>
            <input type="text" name="username" required placeholder="Mfano: JohnDoe">
        </div>
        <div class="form-group">
            <label>Barua Pepe (Email)</label>
            <input type="email" name="email" required placeholder="Mfano: john@gmail.com">
        </div>
        <div class="form-group">
            <label>Nenosiri (Password)</label>
            <input type="password" name="password" required placeholder="Usiishare na mtu">
        </div>
        <button type="submit" class="btn">Tengeneza Akaunti</button>
    </form>
    
    <p>Tayari una akaunti? <a href="index.php">Ingia hapa</a></p>
</div>

</body>
</html>