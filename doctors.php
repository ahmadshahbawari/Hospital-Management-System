<?php
declare(strict_types=1);
require_once __DIR__ . '/dbconnection.php';
$pageTitle = 'Our Doctors';
require __DIR__ . '/professional_assets/modern_header.php';

$doctors = [];
$result = $con->query("SELECT d.doctorname, d.education, d.experience, d.consultancy_charge, dept.departmentname
                      FROM doctor d
                      LEFT JOIN department dept ON dept.departmentid=d.departmentid
                      WHERE d.status='Active'
                      ORDER BY d.doctorname");
while ($row = $result->fetch_assoc()) $doctors[] = $row;
?>
<main>
<section class="section"><div class="container">
  <div class="section-head"><div><span class="eyebrow">Care team</span><h2>Our Doctors</h2><p>Meet the clinicians available through Station Hospital.</p></div>
  <a class="btn btn-primary" href="patientlogin.php">Book an appointment</a></div>
  <div class="search-box"><input type="search" placeholder="Search doctors or departments…" data-filter="#doctorList" aria-label="Search doctors"></div>
  <div class="doctor-grid" id="doctorList">
  <?php if (!$doctors): ?>
    <div class="card"><h3>No doctors available</h3><p>Please check the doctor records in the database.</p></div>
  <?php else: foreach ($doctors as $d):
    $name=$d['doctorname'] ?? 'Doctor';
    $initial=strtoupper(substr(trim($name),0,1));
  ?>
    <article class="doctor-card" data-search-item>
      <div class="doctor-avatar"><?= htmlspecialchars($initial) ?></div>
      <h3><?= htmlspecialchars($name) ?></h3>
      <span class="badge"><?= htmlspecialchars($d['departmentname'] ?? 'Medical Department') ?></span>
      <p><strong>Education:</strong> <?= htmlspecialchars($d['education'] ?? '—') ?><br>
      <strong>Experience:</strong> <?= htmlspecialchars($d['experience'] ?? '—') ?></p>
      <a class="btn btn-light" href="patientlogin.php">Request appointment</a>
    </article>
  <?php endforeach; endif; ?>
  </div>
</div></section>
<section class="section"><div class="container"><div class="cta">
  <h2>Need help choosing a doctor?</h2><p>Contact the hospital team and we can guide you to the right department.</p>
  <a class="btn btn-accent" href="contact.php">Contact us</a>
</div></div></section>
</main>
<?php require __DIR__ . '/professional_assets/modern_footer.php'; ?>
