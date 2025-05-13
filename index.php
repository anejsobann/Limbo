<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
  header('Location: login.php');
  exit;
}
$username = $_SESSION['username'];
?>
<?php
$balance = $_SESSION['balances'][$username];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Limbo</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <div class="bet-section">
        <label for="bet-amount">Bet Amount</label>
        <input id="bet-amount" type="number" step="0.00000001" name="bet" value="<?= isset($_POST['bet']) ? htmlspecialchars($_POST['bet']) : '0.00' ?>" />
        <div class="bet-buttons">
          <button onclick="adjustBet(0.5)">½</button>
          <button onclick="adjustBet(2)">2×</button>
        </div>
        <label for="profit-on-win">Profit on Win</label>
        <input id="profit-on-win" type="text" disabled value="0.00000000" />
        <button class="bet-button" onclick="placeBet()">Bet</button>
        <div style="margin-top: 10px; font-size: 14px;">Balance: €<?= number_format($_SESSION['balances'][$username], 2) ?></div>
      </div>
    </div>
    <div class="main-display">
      <div id="multiplier-display">1.00×</div>
    </div>
    <div class="bottom-panel">
      <label for="target-multiplier">Target Multiplier</label>
      <input id="target-multiplier" type="number" step="0.01" name="multiplier" value="<?= isset($_POST['multiplier']) ? htmlspecialchars($_POST['multiplier']) : '2.00' ?>" />
      <label for="win-chance">Win Chance</label>
      <input id="win-chance" type="text" value="49.50000000" disabled />
    </div>
  </div>

  <form method="POST" style="display: none;" id="betForm">
    <input type="hidden" name="bet" id="php-bet" />
    <input type="hidden" name="multiplier" id="php-multiplier" />
  </form>

  <?php
  if (!isset($_SESSION['bets'][$username])) {
    $_SESSION['bets'][$username] = [];
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bet = floatval($_POST['bet']);
    $multiplier = floatval($_POST['multiplier']);
    $roll = rand(1, 10000) / 100;
    $actual = 99 / $roll;
    $win = $actual >= $multiplier;
    $profit = $win ? $bet * $multiplier : 0;
    $_SESSION['balances'][$username] += ($profit - $bet);

    $_SESSION['bets'][$username][] = [
      'bet' => $bet,
      'target' => $multiplier,
      'rolled' => number_format($actual, 2),
      'result' => $win ? 'Win' : 'Lose',
      'profit' => number_format($profit, 8)
    ];
  }
  ?>

  

  <script src="script.js"></script>

      <div style="position: absolute; bottom: 20px; left: 20px;">
        <a href="logout.php" style="display: inline-block; padding: 15px; background-color: #00ff6a; color: #000; font-weight: bold; text-decoration: none; border-radius: 5px;">Logout</a>
      </div>

</body>
</html>
