<?php
session_start();
include 'includes/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$name || !$email || !$password) {
        $errors[] = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
           $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
           


            $stmt->bind_param("sss", $name, $email, $hashed);
            $stmt->execute();
            $_SESSION['user_id'] = $stmt->insert_id;
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
        input[type="text"],
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
        <h2>Register</h2>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
        <form method="post" action="register.php">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" type="submit">Register</button>
        </form>
        <div class="link">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>
</body>
</html>
