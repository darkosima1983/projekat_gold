<?php
require_once "Models/User.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
     if (!isset ($_POST["email"]) || empty($_POST["email"])) {
        die ("Niste uneli korisničko ime");
    }
    if (!isset ($_POST["password"]) || empty($_POST["password"])) {
        die ("Niste uneli lozinku ");
    }
    $login = new User();
    $login->login($_POST["email"],$_POST["password"]);
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Uloguj se</title>
     <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
     <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <img src="img/logo.png" alt="Logo" class="logo">
    <nav>
      <a href="index.php">Početna</a>
      <a href="registracija.php">Registracija</a>
       <?php if (isset($_SESSION['log']) && $_SESSION['log'] === true): ?>
      <a href="kalkulator.php">Kalkulator</a>
      <a href="Models/session_stop.php">Izloguj se</a>
            <?php else: ?>
      <a href="login.php">Uloguj se</a>
            <?php endif; ?>
    </nav>
  </header>

  <main>
    <h1>Uloguj se</h1>
    <form action="#" method = "POST">
      <input type="email" placeholder="Email" required name= "email">
      <input type="password" placeholder="Lozinka" required name= "password">
      <button type="submit">Uloguj se</button>
    </form>
  </main>

  <footer>
    <p>&copy; 2025 ZlatoKalkulator</p>
  </footer>
</body>
</html>
