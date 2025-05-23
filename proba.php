<?php
$apiKey = "x259b4bb3k5q20zv44o56e0pa53udbz0fhploq7jff77c1ntos46hsy32sta";
$url = "https://metals-api.com/api/latest?access_key=$apiKey&base=USD&symbols=XAU,EUR";

// Pokreni cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Dekodiraj JSON
$data = json_decode($response, true);

// Provera
if (isset($data['rates']['XAU']) && isset($data['rates']['EUR'])) {
    $xauRate = $data['rates']['XAU']; // koliko 1 USD vredi u XAU
    $eurRate = $data['rates']['EUR']; // koliko 1 USD vredi u EUR

    // Cena 1 unce zlata u USD = 1 / XAU
    $goldUsd = 1 / $xauRate;

    // Pretvori u EUR
    $goldEur = $goldUsd * $eurRate;

    echo "<h2>Trenutna cena zlata (1 unca) u EUR: €" . number_format($goldEur, 2) . "</h2>";
} else {
    echo "Greška pri dohvatanju podataka.";
}
?>
