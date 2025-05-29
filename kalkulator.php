<?php
session_start();
require_once "Models/Baza.php";
require_once "Models/User.php";


$apiKey = "x259b4bb3k5q20zv44o56e0pa53udbz0fhploq7jff77c1ntos46hsy32sta"; // Deklariše promenljivu $apiKey i u nju upisuje tvoj API ključ
$url = "https://metals-api.com/api/latest?access_key=$apiKey&base=USD&symbols=XAU,EUR"; // Formira URL za API poziv (GET zahtev) prema Metals-API servisu.


$ch = curl_init($url); // Inicijalizuje cURL sesiju (otvara konekciju). Vraća cURL handler ($ch) koji se koristi u sledećim cURL funkcijama za podešavanje i izvršavanje zahteva.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //CURLOPT_RETURNTRANSFER: kaže cURL-u da ne ispisuje direktno odgovor na ekran, već da ga vrati kao string. Kada je true, curl_exec($ch) neće prikazati rezultat na stranici, već ćeš moći da ga sačuvaš u promenljivu (kao što je $response) i dalje obrađuješ (npr. json_decode()).
$response = curl_exec($ch); // Izvršava HTTP zahtev koji je pripremljen pomoću $ch (handler). $response će sadržati JSON odgovor sa kursevima zlata i evra
curl_close($ch);
$data = json_decode($response, true); // Dekodiraj JSON. Kada je true, rezultat će biti asocijativni niz (array) umesto objekta

// Provera
if (isset($data['rates']['XAU']) && isset($data['rates']['EUR'])) {
    $xauRate = $data['rates']['XAU']; // koliko 1 USD vredi u XAU
    $eurRate = $data['rates']['EUR']; // koliko 1 USD vredi u EUR

    
    $goldUsd = 1 / $xauRate; // Cena 1 unce zlata u USD = 1 / XAU

    
    $goldEur = $goldUsd * $eurRate; // Pretvori u EUR
}
$goldQuantity = isset($_GET['quantity']) ? floatval($_GET['quantity']) : 0;// uzima njegovu vrednost i konvertuje je u decimalni broj (float) pomoću floatval()
$gramsPerOunce = 31.1035;
$quantityOunce = $goldQuantity / $gramsPerOunce;
$currentPrice = $goldEur * $quantityOunce;

$date7daysAgo = date('Y-m-d', strtotime('-7 days')); // Pretvara tekst "-7 days" u UNIX timestamp (vreme pre tačno 7 dana).
$url7days = "https://metals-api.com/api/$date7daysAgo?access_key=$apiKey&base=USD&symbols=XAU,EUR"; // Formira pun URL za pozivanje istorijskih podataka 

$ch7 = curl_init($url7days);
curl_setopt($ch7, CURLOPT_RETURNTRANSFER, true);
$response7 = curl_exec($ch7);
curl_close($ch7);
$data7 = json_decode($response7, true);

if (isset($data7['rates']['XAU']) && isset($data7['rates']['EUR'])) {
    $goldUsd7 = 1 / $data7['rates']['XAU'];
    $goldEur7 = $goldUsd7 * $data7['rates']['EUR'];
    $oldPrice = $goldEur7 * $quantityOunce;
} else {
    $oldPrice = null;
}

if ($oldPrice !== null) { //Ako smo uspeli da dobijemo staru cenu iz API-ja (pre 7 dana), onda možemo da računamo
    $priceDifference = abs($currentPrice - $oldPrice); // uvek pozitivna promena
    $projection = $currentPrice + $priceDifference;
    $profit = $currentPrice - $oldPrice;
} else {
    $projection = null;
    $profit = null;
}

if (isset($_GET['action']) && $_GET['action'] === 'pdf') { //Ako "akcija" postoji, proverava da li je tačno string "pdf"
   
    require_once('libs/tcpdf/tcpdf.php'); // Putanja do TCPDF fajla

    $pdf = new TCPDF(); // Kreira novi PDF dokument koristeći TCPDF biblioteku.
    $pdf->AddPage();

    $firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
    $lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
    $user = trim("$firstName $lastName");

    $html = "<h1>Izveštaj o zlatu za korisnika: <b>$user</b></h1>";
    $html .= "<p>Kolicina: {$goldQuantity} g</p>";
    $html .= "<p>Cena po unci: " . number_format($goldEur, 2) . " EUR</p>"; //Formatira broj tako da ima tačno 2 decimale
    $html .= "<p>Ukupno danas: " . number_format($currentPrice, 2) . " EUR</p>";
    $html .= "<p>Pre 7 dana: " . ($oldPrice !== null ? number_format($oldPrice, 2) . " EUR" : "N/A") . "</p>";
    $html .= "<p>Projekcija za 7 dana: " . ($projection !== null ? number_format($projection, 2) . " EUR" : "N/A") . "</p>";
    $html .= "<p>Profit: " . ($profit !== null ? number_format($profit, 2) . " EUR" : "N/A") . "</p>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('izvestaj_zlato.pdf', 'D'); // 'D' za download

    exit; // Zaustavi dalje izvršavanje stranice
}

?>
<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <title>Kalkulator</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <img src="img/logo.png" alt="Logo" class="logo">
    <nav>
      <a href="index.php">Početna</a>
      <a href="kalkulator.php">Kalkulator</a>
      <a href="registracija.php">Registracija</a>
       <?php if (isset($_SESSION['log']) && $_SESSION['log'] === true): ?>
      <a href="Models/session_stop.php">Izloguj se</a>
      <span style="margin-left: 10px;"><b>Ulogovan: <?php echo ($_SESSION['first_name']); ?></b></span>
            <?php else: ?>
      <a href="login.php">Uloguj se</a>
            <?php endif; ?>
    </nav>
  </header>

  <main>
    <h1>Kalkulator kupovine zlata</h1>
    <form>
      <label for="quantity">Unesite količinu zlata (g):</label>
      <input type="number" id="quantity" name="quantity" placeholder="npr. 10" required>
    

    <div class="rezultat">
      <h2>Rezultati</h2>
      <ul>
        <li>Uneta količina: <?php echo $goldQuantity; ?> g</li>
        <li>Trenutna cena po unci: <?php echo number_format($goldEur, 2); ?> EUR</li>
        <li>Ukupno danas: <?php echo number_format($currentPrice, 2); ?> EUR</li>
        <li>Pre 7 dana: 
          <?php echo $oldPrice !== null ? number_format($oldPrice, 2) . " EUR" : "N/A"; ?>
        </li>
        <li>Za 7 dana (projekcija): 
          <?php echo $projection !== null ? number_format($projection, 2) . " EUR" : "N/A"; ?>
        </li>
        <li>Profit: 
          <?php echo $profit !== null ? number_format($profit, 2) . " EUR" : "N/A"; ?>
        </li>
      </ul>
      <button type="submit" name="action" value="izracunaj">Izračunaj</button>
      <button type="submit" name="action" value="pdf">Exportuj u PDF</button>

    </div>
    </form>
  </main>

  <footer>
    <p>&copy; 2025 ZlatoKalkulator</p>
  </footer>
</body>
</html>
