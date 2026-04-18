<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if (!$result) {
        die("SQL Error: " . $conn->error);
    }

    if ($result->num_rows > 0) {

        $_SESSION['admin'] = $username;

        header("Location: admin/dashboard.php");
        exit();

    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(-45deg, #4facfe, #00f2fe, #ff6ec4, #7873f5);
            background-size: 400% 400%;
            animation: gradientBG 8s ease infinite;
            font-family: Arial;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .card {
            width: 360px;
            border-radius: 20px;
            padding: 25px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            color: white;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        input {
            border-radius: 10px !important;
            padding: 12px !important;
            border: none !important;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            box-shadow: 0 0 10px #00f2fe;
        }

        .btn-login {
            background: #00f2fe;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #4facfe;
            transform: scale(1.03);
        }

        .btn-back {
            margin-top: 10px;
            background: rgba(255,255,255,0.2);
            color: white;
            border-radius: 10px;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.35);
        }

        .alert {
            border-radius: 10px;
        }

        .loader {
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        .dot {
            height: 8px;
            width: 8px;
            margin: 0 2px;
            background-color: white;
            border-radius: 50%;
            display: inline-block;
            animation: bounce 1.2s infinite ease-in-out;
        }

        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>

<body>

<div class="card">

    <h3>🔐 Admin Login</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" onsubmit="showLoader()">

        <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

        <button name="login" class="btn btn-login w-100">
            Login
        </button>

        <div class="loader" id="loader">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>

    </form>

    <a href="index.php" class="btn btn-back w-100">
        ⬅ Back to Home
    </a>

</div>

<script>
function showLoader() {
    document.getElementById("loader").style.display = "block";
}
</script>

</body>
</html>