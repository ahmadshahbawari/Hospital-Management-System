<?php
require_once __DIR__.'/professional_assets/bootstrap_portal.php';
$role=$_SESSION['portal_role']??'';
if(!in_array($role,['admin','patient'],true)){http_response_code(403);exit('Access denied.');}
$where='';$params=[];$types='';
if($role==='patient'){$pid=(int)($_SESSION['patientid']??0);$where=' WHERE patientid=?';$params=[$pid];$types='i';}
$stmt=$con->prepare('SELECT * FROM orders'.$where.' ORDER BY orderid DESC'); if($types)$stmt->bind_param($types,...$params);$stmt->execute();$res=$stmt->get_result();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Orders</title><link rel="stylesheet" href="professional_assets/app.css"><link rel="stylesheet" href="professional_assets/theme.css.php"></head><body class="module-body"><div class="module-wrap"><div class="module-head"><div><span class="eyebrow">ORDERS</span><h1>Orders</h1><p>View hospital orders and order history.</p></div></div><div class="panel"><div class="table-scroll"><table><thead><tr><?php foreach($res->fetch_fields() as $f):?><th><?php echo htmlspecialchars($f->name);?></th><?php endforeach;?></tr></thead><tbody><?php while($row=$res->fetch_assoc()):?><tr><?php foreach($row as $v):?><td><?php echo htmlspecialchars((string)$v);?></td><?php endforeach;?></tr><?php endwhile;?></tbody></table></div></div></div></body></html>
