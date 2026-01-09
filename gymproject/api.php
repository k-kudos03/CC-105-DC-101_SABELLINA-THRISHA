<?php
session_start();
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "GymFlow");

if ($conn->connect_error) die(json_encode(["status" => "error", "message" => "DB Error"]));

$action = $_REQUEST['action'] ?? '';

// Handle Auth
if ($action === 'register') {
    $name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password']; 
    $plan = intval($_POST['plan_id']);
    
    $sql = "INSERT INTO members (full_name, email, password, plan_id) VALUES ('$name', '$email', '$pass', $plan)";
    if ($conn->query($sql)) {
        $_SESSION['user_id'] = $conn->insert_id;
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Email already taken"]);
    }
} 

elseif ($action === 'login') {
    $email = $conn->real_escape_string($_POST['email']);
    $pass = $_POST['password'];
    $res = $conn->query("SELECT * FROM members WHERE email='$email' AND password='$pass'");
    
    if ($user = $res->fetch_assoc()) {
        $_SESSION['user_id'] = $user['member_id'];
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    }
}

// Protected Actions
elseif (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    if ($action === 'get_profile') {
        $sql = "SELECT m.*, p.plan_name, p.monthly_fee, 
                (SELECT COUNT(*) FROM attendance WHERE member_id = m.member_id) as total_days
                FROM members m LEFT JOIN plans p ON m.plan_id = p.plan_id WHERE m.member_id = $uid";
        echo json_encode($conn->query($sql)->fetch_assoc());
    } 
    
    elseif ($action === 'check_in') {
        $sql = "INSERT INTO attendance (member_id) VALUES ($uid)";
        if ($conn->query($sql)) {
            echo json_encode(["status" => "success", "message" => "Checked in!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Already checked in today."]);
        }
    }

    elseif ($action === 'logout') {
        session_destroy();
        echo json_encode(["status" => "success"]);
    }
}
?>