<?php
declare(strict_types=1);
require_once __DIR__ . '/dbconnection.php';

$php = PHP_VERSION;
$mysqli = $con->server_info;
$result = $con->query("SHOW TABLES");
$tables = [];
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Hospital System — Environment Check</title>
<style>
body{font-family:Arial,sans-serif;max-width:900px;margin:40px auto;line-height:1.5}
.ok{color:#176b2c}.bad{color:#9b1c1c}code{background:#f3f3f3;padding:2px 5px}
</style>
</head>
<body>
<h1>Environment Check</h1>
<p class="ok"><strong>PHP:</strong> <?= htmlspecialchars($php, ENT_QUOTES, 'UTF-8') ?></p>
<p class="ok"><strong>MySQL/MariaDB:</strong> <?= htmlspecialchars($mysqli, ENT_QUOTES, 'UTF-8') ?></p>
<p class="ok"><strong>Database:</strong> station</p>
<h2>Tables detected</h2>
<ul>
<?php foreach ($tables as $table): ?>
<li><?= htmlspecialchars($table, ENT_QUOTES, 'UTF-8') ?></li>
<?php endforeach; ?>
</ul>
<p>If this page loads and shows the expected tables, the PHP-to-database connection is working.</p>
</body>
</html>
