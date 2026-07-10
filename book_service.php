<?php
// book_service.php
session_start();
require_once 'Service.php';

// Ruhusu mteja tu kuingiza oda
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['service_id'])) {
    $serviceManager = new Service();
    $db = $serviceManager->conn; // Inatumia connection ya PDO zilizopo

    $user_id = intval($_SESSION['user_id']);
    $service_id = intval($_GET['service_id']);
    $appointment_date = date('Y-m-d H:i:s'); // Inajaza tarehe na muda wa sasa hivi
    $status = 'Inasubiri';

    // Inatumia majina halisi ya column kulingana na .sql yako
    $query = "INSERT INTO appointments (user_id, service_id, appointment_date, status) 
              VALUES (:user_id, :service_id, :appointment_date, :status)";
              
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':service_id', $service_id);
    $stmt->bindParam(':appointment_date', $appointment_date);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        header("Location: dashboard.php?status=booked");
    } else {
        header("Location: dashboard.php?status=error");
    }
    exit();
}

header("Location: dashboard.php");
?>