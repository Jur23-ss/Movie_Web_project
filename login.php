<?php
session_start();
include 'includes/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed);
    if ($stmt->fetch()) {
        if (password_verify($password, $hashed)) {
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        $errors[] = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #020307;
            font-family: 'Poppins', sans-serif;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .form-container {
            background: #1c1c1c;
            padding: 2rem 3rem;
            border-radius: 10px;
            box-shadow: 0 0 10px #ff2c1f44;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #ff2c1f;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 5px;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #ff2c1f;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background: #0c4090;
        }
        .error {
            color: #ff4d4d;
            margin-bottom: 1rem;
        }
        .link {
            text-align: center;
            margin-top: 1rem;
        }
        .link a {
            color: #ff2c1f;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
        <form method="post" action="login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" type="submit">Log In</button>
        </form>
        <div class="link">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>
