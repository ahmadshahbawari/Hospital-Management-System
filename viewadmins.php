<?php
session_start();
if (!isset($_SESSION['adminid']) && ($_SESSION['portal_role'] ?? '') !== 'admin') {
    header('Location: index.php');
    exit;
}
require_once 'dbconnection.php';

$msg = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_admin'])) {
    $id = (int)($_POST['adminid'] ?? 0);
    $currentId = (int)($_SESSION['adminid'] ?? $_SESSION['portal_id'] ?? 0);
    if ($id <= 0) {
        $error = 'Invalid administrator record.';
    } elseif ($id === $currentId) {
        $error = 'You cannot delete the administrator account currently in use.';
    } else {
        $stmt = $con->prepare('DELETE FROM admin WHERE adminid = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $msg = $stmt->affected_rows ? 'Administrator deleted successfully.' : 'Administrator record was not found.';
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Administrators | Station Hospital</title>
<link rel="stylesheet" href="professional_assets/app.css"><link rel="stylesheet" href="professional_assets/theme.css.php">
<style>
.page{max-width:1100px;margin:0 auto;padding:26px}.page-head{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px}.page-head h1{margin:0;font-size:30px}.page-head p{margin:6px 0 0;color:#718096}.panel{background:var(--card,#fff);border:1px solid var(--line,#e5ebf2);border-radius:18px;padding:22px;box-shadow:var(--shadow,0 14px 40px rgba(24,48,75,.08))}.toolbar{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px}.search{width:min(380px,100%);padding:11px 13px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--text)}.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse}th,td{padding:13px 12px;border-bottom:1px solid var(--line);text-align:left}th{font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:#718096}tr:hover td{background:rgba(44,120,212,.035)}.actions{display:flex;gap:8px;align-items:center}.btn{display:inline-flex;align-items:center;justify-content:center;padding:9px 13px;border-radius:9px;text-decoration:none;border:1px solid var(--line);font-weight:700;font-size:13px;background:var(--card);color:var(--text);cursor:pointer}.btn-edit{background:#edf5ff;border-color:#cfe3fa;color:#1767ad}.btn-delete{background:#fff0f1;border-color:#f5c8cd;color:#b12f3e}.alert{padding:12px 14px;border-radius:10px;margin-bottom:16px}.success{background:#e9f8ef;color:#166534;border:1px solid #bfe9cc}.danger{background:#fff0f1;color:#a52d3b;border:1px solid #f5c8cd}@media(max-width:650px){.page{padding:16px}.page-head,.toolbar{align-items:flex-start;flex-direction:column}.search{width:100%}.actions{flex-wrap:wrap}}
</style>
</head>
<body class="module-body">
<div class="page">
  <div class="page-head"><div><span class="eyebrow">ADMINISTRATION</span><h1>Administrator Accounts</h1><p>Manage administrator access, status and account details.</p></div><a class="primary-btn" href="addadmin.php">＋ Add Administrator</a></div>
  <?php if ($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="alert danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <div class="panel">
    <div class="toolbar"><strong>All administrators</strong><input id="adminSearch" class="search" type="search" placeholder="Search administrators…"></div>
    <div class="table-wrap"><table id="adminTable"><thead><tr><th>Admin Name</th><th>Login ID</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php
    $q = $con->query('SELECT adminid, adminname, loginid, status FROM admin ORDER BY adminid DESC');
    while ($rs = $q->fetch_assoc()):
    ?>
      <tr>
        <td><?= htmlspecialchars((string)$rs['adminname']) ?></td>
        <td><?= htmlspecialchars((string)$rs['loginid']) ?></td>
        <td><?= htmlspecialchars((string)$rs['status']) ?></td>
        <td><div class="actions"><a class="btn btn-edit" href="addadmin.php?editid=<?= (int)$rs['adminid'] ?>">✎ Edit</a><form method="post" onsubmit="return confirm('Delete this administrator account? This action cannot be undone.');"><input type="hidden" name="adminid" value="<?= (int)$rs['adminid'] ?>"><button class="btn btn-delete" type="submit" name="delete_admin" value="1">⌫ Delete</button></form></div></td>
      </tr>
    <?php endwhile; ?>
    </tbody></table></div>
  </div>
</div>
<script>const s=document.getElementById('adminSearch');s?.addEventListener('input',()=>{const v=s.value.toLowerCase();document.querySelectorAll('#adminTable tbody tr').forEach(r=>r.style.display=r.textContent.toLowerCase().includes(v)?'':'none');});</script>
</body></html>
