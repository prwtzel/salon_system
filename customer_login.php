<?php
session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if ($customer && password_verify($password, $customer['password'])) {
        $_SESSION['user_id'] = $customer['id'];
        $_SESSION['name'] = $customer['name'];
        header("Location: customer_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh; background:#f8f9fa;">

<div class="card p-4 shadow" style="width:350px; border-radius:10px;">

    <h3 class="text-center mb-3">💇 Customer Login</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

        <button name="login" class="btn btn-primary w-100">Login</button>

    </form>

    <!-- REGISTER BUTTON -->
    <a href="index.php" class="btn btn-secondary w-100 mt-3">
    back to Home
    </a>

</div>

</body>
</html>