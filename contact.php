<?php
declare(strict_types=1);
$pageTitle = 'Contact Us';
require __DIR__ . '/professional_assets/modern_header.php';
?>
<main>
<section class="hero"><div class="container hero-grid">
  <div><span class="eyebrow">We're here to help</span><h1>Contact Station Hospital</h1>
  <p>Reach our team for appointments, general questions, directions, or patient support.</p>
  <div class="hero-actions">
    <a class="btn btn-primary" href="tel:+0000000000">📞 Place a call</a>
    <a class="btn btn-accent" href="https://wa.me/" target="_blank" rel="noopener">💬 Chat with us</a>
    <a class="btn btn-light" href="patientlogin.php">📅 Book appointment</a>
  </div></div>
  <div class="hero-card"><h3>Contact details</h3>
    <p><strong>Phone</strong><br><a href="tel:+0000000000">Hospital phone number</a></p>
    <p><strong>Email</strong><br><a href="mailto:info@example.com">info@example.com</a></p>
    <p><strong>Hours</strong><br>Emergency support: 24/7<br>General enquiries: Mon–Sat</p>
  </div>
</div></section>
<section class="section"><div class="container">
  <div class="section-head"><div><span class="eyebrow">Support</span><h2>How can we help?</h2></div></div>
  <div class="cards">
    <article class="card"><div class="icon">📅</div><h3>Appointments</h3><p>Book or manage an appointment through the patient portal.</p><a class="btn btn-light" href="patientlogin.php">Book now</a></article>
    <article class="card"><div class="icon">👨‍⚕️</div><h3>Find a doctor</h3><p>Browse active doctors and their departments.</p><a class="btn btn-light" href="doctors.php">View doctors</a></article>
    <article class="card"><div class="icon">💬</div><h3>Chat & enquiries</h3><p>Use WhatsApp or email for general questions.</p><a class="btn btn-light" href="https://wa.me/" target="_blank" rel="noopener">Start chat</a></article>
  </div>
</div></section>
<section class="section"><div class="container"><div class="form-card">
  <h2>Send an enquiry</h2><p>For this demo build, this form prepares an email in your mail application.</p>
  <form action="mailto:info@example.com" method="post" enctype="text/plain">
    <div class="form-grid"><div><label>Your name<input name="name" required></label></div>
    <div><label>Email<input type="email" name="email" required></label></div></div>
    <div style="margin-top:15px"><label>Message<textarea name="message" rows="5" required></textarea></label></div>
    <button class="btn btn-primary" type="submit" style="margin-top:15px">Send enquiry</button>
  </form>
</div></div></section>
</main>
<?php require __DIR__ . '/professional_assets/modern_footer.php'; ?>
