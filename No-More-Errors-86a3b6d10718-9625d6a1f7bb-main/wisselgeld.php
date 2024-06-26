<?php

define('MONEY_UNITS', [
    5000 => '50 euro',
    2000 => '20 euro',
    1000 => '10 euro',
    500  => '5 euro',
    200  => '2 euro',
    100  => '1 euro',
    50   => '50 cent',
    20   => '20 cent',
    10   => '10 cent',
    5    => '5 cent'
]);

function calculateChange($amount) 
{
    $change = [];

    // Rond het bedrag af naar het dichtstbijzijnde hele getal
    $restbedrag = round($amount);

    foreach (MONEY_UNITS as $value => $name) {
        if ($restbedrag >= $value) {
            $aantalKeerGeldEenheidInRestBedrag = floor($restbedrag / $value);
            $restbedrag %= $value;
            $change[$name] = $aantalKeerGeldEenheidInRestBedrag;
        }
    }

    return $change;
}

function displayChange($change) 
{
    foreach ($change as $coin => $count) {
        echo "{$count} x {$coin}\n";
    }
}

function formatCurrency($amount) 
{
    return number_format($amount, 2, ',', '.') . ' euro';
}

// Functie om bedrag af te ronden naar het dichtstbijzijnde 5 cent
function roundToNearest5Cents($amount) 
{
    return round($amount * 2, 1) / 2;
}

try {
    // Controleer of de bedragen zijn ingevoerd via de commandoregel
    if ($argc < 2) {
        throw new Exception("Geen bedrag meegegeven");
    }

    $input = $argv[1];

    // Controleer of het bedrag een geldig getal is
    if (!preg_match('/^-?\d+(\.\d+)?$/', $input)) {
        throw new Exception("Geen wisselgeld");
    }

    // Het totale wisselgeld
    $bedrag = floatval($input);

    // Controleer of het bedrag negatief is
    if ($bedrag < 0) {
        throw new Exception("Negatief bedrag meegegeven");
    }

    $afgerondBedrag = roundToNearest5Cents($bedrag);

    // Bereken het wisselgeld in munten en biljetten
    $change = calculateChange($afgerondBedrag * 100); // Converteer naar centen voor berekening

    // Laat het aantal munten en biljetten zien
    displayChange($change);
} catch (Exception $e) {
    echo $e->getMessage() . "\n"; 
    exit(1);
}

?>
