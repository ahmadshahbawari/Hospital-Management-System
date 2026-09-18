<?php
/**
 * Shared legacy page header.
 * The old horizontal menu has intentionally been removed. All authenticated
 * navigation now lives in the professional sidebar rendered by index.php.
 */
error_reporting(0);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once("dbconnection.php");
$dt = date("Y-m-d");
$tim = date("H:i:s");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>STATION HOSPITAL</title>
<link rel="stylesheet" href="professional_assets/app.css" type="text/css"><link rel="stylesheet" href="professional_assets/theme.css.php" type="text/css">
<link rel="stylesheet" href="css/font-awesome.css" type="text/css">
<style>
/* Legacy modules are displayed inside the professional dashboard workspace. */
html,body{margin:0;padding:0;min-height:100%;}
body{font-family:Inter,Segoe UI,Arial,sans-serif;background:#f4f7fb;color:#172033;}
body::-webkit-scrollbar,html::-webkit-scrollbar{width:7px;height:7px}
body::-webkit-scrollbar-thumb,html::-webkit-scrollbar-thumb{background:#aeb9c8;border-radius:999px}
body::-webkit-scrollbar-track,html::-webkit-scrollbar-track{background:transparent}
</style>
<script>
(function(){
  try{
    var lang=localStorage.getItem('hms-lang')||'en';
    document.documentElement.lang=lang;
    document.documentElement.dir=(lang==='ps'||lang==='fa')?'rtl':'ltr';
  }catch(e){}
})();
</script>
</head>
<style>
html,body{max-width:100%;overflow-x:hidden}body{background:#f4f7fb!important;color:#172033!important;font-family:Inter,Segoe UI,Arial,sans-serif!important;padding:0!important;margin:0!important}body>.wrapper,body>.col4,#container{box-sizing:border-box}#container{max-width:1180px!important;margin:0 auto!important;padding:34px 42px 50px!important}#container h1,#container h2{color:#172033;margin:0 0 22px!important;line-height:1.25}#container form{background:#fff;border:1px solid #e5ebf2;border-radius:18px;padding:30px!important;box-shadow:0 10px 30px rgba(30,50,90,.06)}#container input,#container select,#container textarea{max-width:100%;box-sizing:border-box}#container input[type=text],#container input[type=password],#container input[type=date],#container input[type=time],#container input[type=search],#container select,#container textarea{background:#fff!important;color:#172033!important;border:1px solid #d8dfeb!important;border-radius:10px!important;padding:12px 14px!important;margin:7px 0 16px!important;min-height:44px}#container input[type=submit],#container button{border-radius:10px!important;background:#10253f!important;color:#fff!important;padding:13px 18px!important;border:0!important}#container table{width:100%;border-collapse:collapse;background:#fff;border-radius:14px;overflow:hidden}#container th,#container td{padding:12px!important;border-bottom:1px solid #edf0f5!important}#container th{background:#10253f!important;color:#fff!important}.clear{display:none}.legacy-card{margin-bottom:22px}@media(max-width:800px){#container{padding:24px 18px 40px!important}#container form{padding:20px!important}#container table{display:block;overflow:auto;white-space:nowrap}}
html[dir=rtl] body{direction:rtl}html[dir=rtl] #container{text-align:right}html[dir=rtl] #container table{text-align:right}
</style>

<body>
