<?php
session_start();
if (!isset($_SESSION['adminid']) && ($_SESSION['portal_role'] ?? '') !== 'admin') { header('Location: index.php'); exit; }
require_once 'dbconnection.php';

$editId = (int)($_GET['editid'] ?? 0);
$msg = '';
$error = '';
$rsedit = ['adminname'=>'','loginid'=>'','status'=>'Active'];
if ($editId > 0) {
    $stmt = $con->prepare('SELECT adminid, adminname, loginid, status FROM admin WHERE adminid=? LIMIT 1');
    $stmt->bind_param('i', $editId); $stmt->execute(); $rsedit = $stmt->get_result()->fetch_assoc() ?: $rsedit; $stmt->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim((string)($_POST['adminname'] ?? ''));
    $login = trim((string)($_POST['loginid'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['cnfirmpassword'] ?? '');
    $status = (string)($_POST['status'] ?? '');
    if ($name === '' || $login === '' || !in_array($status, ['Active','Inactive'], true)) $error = 'Please complete all required fields.';
    elseif ($editId === 0 && $password === '') $error = 'Password is required for a new administrator.';
    elseif ($password !== '' && $password !== $confirm) $error = 'Passwords do not match.';
    else {
        $check = $con->prepare('SELECT adminid FROM admin WHERE loginid=? AND adminid<>? LIMIT 1');
        $check->bind_param('si', $login, $editId); $check->execute();
        if ($check->get_result()->fetch_assoc()) $error = 'That Login ID is already in use.';
        $check->close();
        if (!$error) {
            if ($editId > 0) {
                if ($password !== '') { $hash = password_hash($password, PASSWORD_DEFAULT); $stmt=$con->prepare('UPDATE admin SET adminname=?, loginid=?, password=?, status=? WHERE adminid=?'); $stmt->bind_param('ssssi',$name,$login,$hash,$status,$editId); }
                else { $stmt=$con->prepare('UPDATE admin SET adminname=?, loginid=?, status=? WHERE adminid=?'); $stmt->bind_param('sssi',$name,$login,$status,$editId); }
                $stmt->execute(); $stmt->close(); $msg='Administrator updated successfully.';
            } else {
                $hash=password_hash($password,PASSWORD_DEFAULT); $stmt=$con->prepare('INSERT INTO admin(adminname,loginid,password,status) VALUES(?,?,?,?)'); $stmt->bind_param('ssss',$name,$login,$hash,$status); $stmt->execute(); $stmt->close(); $msg='Administrator created successfully.';
            }
        }
    }
    if ($editId > 0) { $stmt=$con->prepare('SELECT adminid,adminname,loginid,status FROM admin WHERE adminid=? LIMIT 1');$stmt->bind_param('i',$editId);$stmt->execute();$rsedit=$stmt->get_result()->fetch_assoc()?:$rsedit;$stmt->close(); }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $editId?'Edit':'Add' ?> Administrator | Station Hospital</title><link rel="stylesheet" href="professional_assets/app.css"><link rel="stylesheet" href="professional_assets/theme.css.php"><style>.page{max-width:760px;margin:0 auto;padding:26px}.panel{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:26px;box-shadow:var(--shadow)}h1{margin:5px 0}.muted{color:var(--muted)}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:20px}.form-grid label{display:flex;flex-direction:column;gap:7px;font-weight:700;font-size:13px}.form-grid input,.form-grid select{padding:12px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--text)}.full{grid-column:1/-1}.actions{display:flex;gap:10px;margin-top:20px}.secondary{display:inline-flex;padding:12px 15px;border:1px solid var(--line);border-radius:10px;text-decoration:none;color:var(--text)}.alert{padding:12px;border-radius:10px;margin:14px 0}.success{background:#e9f8ef;color:#166534}.danger{background:#fff0f1;color:#a52d3b}@media(max-width:650px){.page{padding:16px}.form-grid{grid-template-columns:1fr}.full{grid-column:auto}}</style></head><body class="module-body"><div class="page"><div class="panel"><span class="eyebrow">ADMINISTRATION</span><h1><?= $editId?'Edit Administrator':'Add Administrator' ?></h1><p class="muted">Manage administrator account information securely.</p><?php if($msg):?><div class="alert success"><?=htmlspecialchars($msg)?></div><?php endif;?><?php if($error):?><div class="alert danger"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post" class="form-grid"><label>Administrator Name<input type="text" name="adminname" required value="<?=htmlspecialchars((string)$rsedit['adminname'])?>"></label><label>Login ID<input type="text" name="loginid" required value="<?=htmlspecialchars((string)$rsedit['loginid'])?>"></label><label>Password<input type="password" name="password" <?= $editId?'':'required' ?> autocomplete="new-password" placeholder="<?= $editId?'Leave blank to keep current password':'' ?>"></label><label>Confirm Password<input type="password" name="cnfirmpassword" <?= $editId?'':'required' ?> autocomplete="new-password"></label><label class="full">Status<select name="status" required><option value="Active" <?=($rsedit['status']==='Active'?'selected':'')?>>Active</option><option value="Inactive" <?=($rsedit['status']==='Inactive'?'selected':'')?>>Inactive</option></select></label><div class="full actions"><button class="primary-btn" type="submit" name="submit" value="1"><?= $editId?'Save Changes':'Create Administrator' ?></button><a class="secondary" href="viewadmins.php">Back to Admins</a></div></form></div></div></body></html>
