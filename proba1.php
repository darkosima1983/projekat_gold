<?php
$apiKey = "x259b4bb3k5q20zv44o56e0pa53udbz0fhploq7jff77c1ntos46hsy32sta";
$today = new DateTime();
$days = 7;
$goldPrices = [];

for ($i = 0; $i < $days; $i++) {
    $date = $today->format('Y-m-d');
    $url = "https://metals-api.com/api/$date?access_key=$apiKey&base=USD&symbols=XAU,EUR";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['rates']['XAU']) && isset($data['rates']['EUR'])) {
        $xauUsd = 1 / $data['rates']['XAU'];
        $eurRate = $data['rates']['EUR'];
        $xauEur = $xauUsd * $eurRate;

        $goldPrices[$date] = number_format($xauEur, 2);
    } else {
        $goldPrices[$date] = "n/a";
    }

    // Pomeri dan unazad
    $today->modify('-1 day');
}

// Prikaz rezultata
echo "<h2>Istorijska cena zlata (1 unca) u EUR:</h2><ul>";
foreach ($goldPrices as $date => $price) {
    echo "<li>$date: €$price</li>";
}
echo "</ul>";
?>