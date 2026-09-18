<?php
declare(strict_types=1);
$pageTitle = 'Services';
require __DIR__ . '/professional_assets/modern_header.php';
?>
<main>
<section class="hero"><div class="container"><span class="eyebrow">Patient services</span><h1>Healthcare designed around you.</h1><p>Explore the main services available through the hospital management system.</p></div></section>
<section class="section"><div class="container"><div class="cards">
<?php
$items=[
['🩺','Doctor consultations','Connect appointments with the appropriate medical department and doctor.'],
['📅','Appointments','Request, approve, and track appointments from the patient portal.'],
['💊','Prescriptions','Keep prescriptions and medicine records organized for patient follow-up.'],
['🧑‍⚕️','Treatment records','Maintain treatment information connected to appointments and clinicians.'],
['🏥','Departments','Browse hospital departments and discover the doctors assigned to them.'],
['📄','Patient records','Keep patient information accessible to authorized hospital staff.'],
];
foreach($items as [$icon,$title,$desc]): ?>
<article class="card"><div class="icon"><?= $icon ?></div><h3><?= htmlspecialchars($title) ?></h3><p><?= htmlspecialchars($desc) ?></p></article>
<?php endforeach; ?>
</div></div></section>
</main>
<?php require __DIR__ . '/professional_assets/modern_footer.php'; ?>
