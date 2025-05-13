function updateWinChance() {
  const multiplier = parseFloat(
    document.getElementById("target-multiplier").value
  );
  if (!isNaN(multiplier) && multiplier > 0) {
    const winChance = 99 / multiplier;
    document.getElementById("win-chance").value = winChance.toFixed(8);
    const betAmount = parseFloat(document.getElementById("bet-amount").value);
    const profit = betAmount * multiplier;
    document.getElementById("profit-on-win").value = profit.toFixed(8);
  }
}

function placeBet() {
  const multiplierTarget = parseFloat(
    document.getElementById("target-multiplier").value
  );
  const betAmount = parseFloat(document.getElementById("bet-amount").value);
  const display = document.getElementById("multiplier-display");

  if (
    isNaN(multiplierTarget) ||
    isNaN(betAmount) ||
    multiplierTarget <= 0 ||
    betAmount <= 0
  ) {
    alert("Please enter valid bet amount and target multiplier.");
    return;
  }

  // Simulate roll
  const roll = Math.random() * 100;
  const actualMultiplier = 99 / roll;
  let start = 1.0;
  const duration = 800;
  const steps = 30;
  let step = 0;

  const increment = (actualMultiplier - start) / steps;

  const interval = setInterval(() => {
    step++;
    const value = start + increment * step;
    display.textContent = value.toFixed(2) + "×";
    if (step >= steps) {
      clearInterval(interval);
      const win = actualMultiplier >= multiplierTarget;
      display.style.color = win ? "lime" : "red";

      // Save to form and submit
      document.getElementById("php-bet").value = betAmount;
      document.getElementById("php-multiplier").value = multiplierTarget;

      setTimeout(() => {
        display.style.color = "white";
        document.getElementById("betForm").submit();
      }, 700);
    }
  }, duration / steps);
}

function adjustBet(factor) {
  const betField = document.getElementById("bet-amount");
  const current = parseFloat(betField.value);
  if (!isNaN(current)) {
    betField.value = (current * factor).toFixed(8);
    updateWinChance();
  }
}
