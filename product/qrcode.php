<?php
header('Content-Type: image/png');
require_once '../vendor/autoload.php';

$qr = new Endroid\QrCode\QrCode();
$text = $_GET['text'];
$qr->setText($text);
$qr->setSize(200);
$qr->setPadding(10);

$qr->render();
