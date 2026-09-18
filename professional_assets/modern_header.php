<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? 'Station Hospital';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | Station Hospital</title>
<link rel="stylesheet" href="professional_assets/modern.css">
</head>
<body>
<div class="topbar"><div class="container">
  <span>24/7 Patient Support</span>
  <span><a href="tel:+0000000000">Call us</a> · <a href="https://wa.me/" target="_blank" rel="noopener">Chat on WhatsApp</a></span>
</div></div>
<nav class="navbar"><div class="container">
  <a class="brand" href="index.php"><span class="brand-mark">+</span><span>Station Hospital</span></a>
  <button class="btn btn-light mobile-menu" type="button" data-mobile-toggle aria-label="Open menu">☰</button>
  <div class="nav-links">
    <a data-nav href="index.php">Home</a>
    <a data-nav href="doctors.php">Our Doctors</a>
    <a data-nav href="contact.php">Contact</a>
    <a data-nav href="patientlogin.php">Appointments</a>
    <a class="btn btn-primary" href="patientlogin.php">Patient Portal</a>
  </div>
</div></nav>
