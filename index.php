<?php
require_once "Models/Baza.php";
require_once "Models/User.php";
?>
<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Početna | Cene Zlata</title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <img src="img/logo.png" alt="Logo" class="logo">
    <nav>
      <a href="index.php">Početna</a>
      <a href="registracija.php">Registracija</a>
      <?php if (isset($_SESSION['log'])): ?>
      <a href="kalkulator.php">Kalkulator</a>
      <a href="Models/session_stop.php">Izloguj se</a>
      <span style="margin-left: 10px;"><b>Ulogovan: <?php echo ($_SESSION['first_name']); ?></b></span>
            <?php else: ?>
      <a href="login.php">Uloguj se</a>
            <?php endif; ?>
    </nav>
  </header>

  <main>
    <h1>Dobrodošli na sajt za praćenje cena zlata</h1>
    <p>Pratite dnevne cene, računajte isplativost i analizirajte potencijalni profit od kupovine zlata.</p>
    <p>Da bi koristili klakulator za zlato morate se registrovati <a href="registracija.php">OVDE</a></p>
    <img src="img/logo1.png" alt="Zlatni kalkulator" class="center-img">
  </main>

  <footer>
    <p>&copy; 2025 ZlatoKalkulator</p>
  </footer>
</body>
</html>
