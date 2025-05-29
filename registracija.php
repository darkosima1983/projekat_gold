<?php
require_once "Models/Baza.php";
require_once "Models/User.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
      if ( !isset ($_POST["first_name"]) || empty($_POST["first_name"]))
    {
        die ("Niste uneli ime");
    }
      if ( !isset ($_POST["last_name"]) || empty($_POST["last_name"]))
    {
        die ("Niste uneli prezime");
    }
    if ( !isset ($_POST["email"]) || empty($_POST["email"]))
    {
        die ("Niste uneli email");
    }
    if ( !isset ($_POST["password"]) || empty($_POST["password"]))
    {
        die ("Niste uneli lozinku ");
    }
    $user = new User(); 
    $user->register($_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["password"]);

}    
?>
<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Registracija</title>
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
      <span style="margin-left: 10px;"><b>Ulogovan: <?php echo ($_SESSION['first_name']); ?></b></span>
            <?php else: ?>
      <a href="login.php">Uloguj se</a>
            <?php endif; ?>
    </nav>
  </header>

  <main>
    <h1>Registracija</h1>
    <form method="POST" action="">
      <input type="text" placeholder="Ime" required name="first_name">
      <input type="text" placeholder="Prezime" required name="last_name">
      <input type="email" placeholder="Email" required name="email">
      <input type="password" placeholder="Lozinka" required name="password">
      <button type="submit">Registruj se</button>
    </form>
  </main>

  <footer>
    <p>&copy; 2025 ZlatoKalkulator</p>
  </footer>
</body>
</html>
