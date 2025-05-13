<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $balance = min(max(floatval($_POST['balance']), 0), 100000); // between 0 and 100000

    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = [];
    }

    if (!isset($_SESSION['users'][$username])) {
        $_SESSION['users'][$username] = password_hash($password, PASSWORD_DEFAULT);
        $_SESSION['bets'][$username] = [];
        $_SESSION['balances'][$username] = $balance;
    }

    if (password_verify($password, $_SESSION['users'][$username])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>

    @import url("https://p.typekit.net/p.css?s=1&k=aba0ebl&ht=tk&f=139.173.175.176.10296&a=6570577&app=typekit&e=css");

    @font-face {
    font-family:"proxima-nova";
    src:url("https://use.typekit.net/af/2555e1/00000000000000007735e603/30/l?primer=9e9145798bfc6b7954a6cb7abc2ead67980260945baf1d129a2d2e98d0352745&fvd=n7&v=3") format("woff2"),url("https://use.typekit.net/af/2555e1/00000000000000007735e603/30/d?primer=9e9145798bfc6b7954a6cb7abc2ead67980260945baf1d129a2d2e98d0352745&fvd=n7&v=3") format("woff"),url("https://use.typekit.net/af/2555e1/00000000000000007735e603/30/a?primer=9e9145798bfc6b7954a6cb7abc2ead67980260945baf1d129a2d2e98d0352745&fvd=n7&v=3") format("opentype");
    font-display:auto;font-style:normal;font-weight:700;font-stretch:normal;
    }

    body {
      background-color: #0f1b2a;
      color: white;
      font-family:"proxima-nova";
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background-color: #1b2a3b;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 255, 106, 0.2);
      width: 300px;
      margin-top:-150px;
    }
    h2 {
      margin-bottom: 20px;
      text-align: center;
    }
    input[type="text"],
    input[type="password"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      background-color: #2a3e52;
      border: none;
      color: white;
      border-radius: 5px;
      font-family:"proxima-nova";
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #00ff6a;
      color: #000;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family:"proxima-nova";
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login / Register</h2>
    <?php if (isset($error)) echo '<div class="error">' . $error . '</div>'; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="number" name="balance" step="0.01" value="1000" max="100000" placeholder="Starting Balance (max 100000)" required>
      <button type="submit">Enter</button>
    </form>
  </div>
</body>
</html>
