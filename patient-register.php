<?php
session_start(); require_once __DIR__.'/dbconnection.php';
$msg='';$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name=trim($_POST['patientname']??''); $login=trim($_POST['loginid']??''); $pass=(string)($_POST['password']??'');
  $mobile=trim($_POST['mobileno']??''); $dob=$_POST['dob']??''; $gender=trim($_POST['gender']??''); $blood=trim($_POST['bloodgroup']??'');
  if($name===''||$login===''||$pass==='') $error='Name, username and password are required.';
  else {
    $check=$con->prepare("SELECT patientid FROM patient WHERE loginid=? LIMIT 1"); $check->bind_param('s',$login); $check->execute();
    if($check->get_result()->num_rows) $error='Username already exists.';
    else {
      $hash=password_hash($pass,PASSWORD_DEFAULT); $today=date('Y-m-d'); $time=date('H:i:s'); $empty='';
      $s=$con->prepare("INSERT INTO patient(patientname,admissiondate,admissiontime,address,mobileno,city,pincode,loginid,password,bloodgroup,gender,dob,status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,'Active')");
      $s->bind_param('sssssssssssss',$name,$today,$time,$empty,$mobile,$empty,$empty,$login,$hash,$blood,$gender,$dob);
      $s->execute(); $msg='Account created. You can now sign in as Patient / User.';
    }
  }
}
?><!doctype html><html><head><meta charset="utf-8"><title>Create Patient Account</title><link rel="stylesheet" href="professional_assets/app.css"><link rel="stylesheet" href="professional_assets/theme.css.php"></head><body class="module-body"><div class="module-card narrow"><h1>Create Patient Account</h1><?php if($msg):?><div class="success"><?php echo htmlspecialchars($msg);?></div><?php endif;?><?php if($error):?><div class="alert"><?php echo htmlspecialchars($error);?></div><?php endif;?><form method="post" class="form-grid"><label>Full name<input name="patientname" required></label><label>Username<input name="loginid" required></label><label>Password<input type="password" name="password" required minlength="6"></label><label>Mobile<input name="mobileno"></label><label>Date of birth<input type="date" name="dob"></label><label>Gender<select name="gender"><option value="">Select</option><option>Male</option><option>Female</option></select></label><label>Blood group<select name="bloodgroup"><option value="">Select</option><?php foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $b) echo '<option>'.htmlspecialchars($b).'</option>';?></select></label><button class="primary-btn">Create account</button></form><p><a href="index.php">← Back to login</a></p></div></body></html>
