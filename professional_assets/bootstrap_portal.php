<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__.'/../dbconnection.php';
function p_user(): array { return ['role'=>$_SESSION['portal_role']??'', 'id'=>$_SESSION['portal_id']??0, 'name'=>$_SESSION['portal_name']??'']; }
function require_role(array $roles): void { $r=$_SESSION['portal_role']??''; if(!$r || !in_array($r,$roles,true)){ http_response_code(403); exit('Access denied.'); } }
function log_action(mysqli $c,string $action,string $details=''):void{ $u=$_SESSION['portal_login']??'';$r=$_SESSION['portal_role']??''; try{$q=$c->prepare('INSERT INTO audit_logs(loginid,role_type,action,details) VALUES(?,?,?,?)');$q->bind_param('ssss',$u,$r,$action,$details);$q->execute();}catch(Throwable $e){} }
function setting(mysqli $c,string $key,string $default=''):string{try{$q=$c->prepare('SELECT setting_value FROM system_settings WHERE setting_key=?');$q->bind_param('s',$key);$q->execute();$r=$q->get_result()->fetch_assoc();return $r?(string)$r['setting_value']:$default;}catch(Throwable $e){return $default;}}
function esc(string $s):string{return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
