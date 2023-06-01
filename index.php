<?php
include 'CBRCurrency.php';

$date = new DateTime();
$from = new DateTime(date('d.m.Y', strtotime("-90 days")));

$cbr = new CBRCurrency();
$result = $cbr->getCurrencyData($from, $date);

echo '<pre>';
var_dump($result);
echo '</pre>';
