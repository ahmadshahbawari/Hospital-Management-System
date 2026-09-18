<?php
declare(strict_types=1);
require_once __DIR__.'/professional_assets/bootstrap_portal.php';
require_role(['admin']);

function xml(string $s): string { return htmlspecialchars($s, ENT_XML1|ENT_QUOTES, 'UTF-8'); }
function colName(int $n): string { $s=''; while($n>0){$n--; $s=chr(65+($n%26)).$s; $n=intdiv($n,26);} return $s; }
function excelText(string $v): string { return xml($v); }

$table=(string)($_GET['table']??'');
$allowed=[];$res=$con->query('SHOW TABLES'); while($r=$res->fetch_row()) $allowed[]=$r[0];
if($table==='' || !in_array($table,$allowed,true)){ http_response_code(400); exit('Invalid export table.'); }

$logoFile='';
try{
  $r=$con->query("SELECT hospital_logo FROM hospital_settings WHERE setting_id=1 LIMIT 1");
  if($r && ($row=$r->fetch_assoc())){
    $candidate=(string)$row['hospital_logo'];
    if($candidate && is_file(__DIR__.'/'.$candidate)) $logoFile=__DIR__.'/'.$candidate;
  }
}catch(Throwable $e){}

$rs=$con->query('SELECT * FROM `'.$con->real_escape_string($table).'`');
$fields=$rs->fetch_fields(); $rows=[];
while($row=$rs->fetch_assoc()) $rows[]=$row;

$templateMap=['doctor'=>'doctors.xlsx','doctors'=>'doctors.xlsx','patient'=>'patients.xlsx','patients'=>'patients.xlsx','pharmacy'=>'pharmacy.xlsx','medicine'=>'pharmacy.xlsx','lab_requests'=>'laboratory.xlsx','laboratory'=>'laboratory.xlsx'];
$templateFile=__DIR__.'/excel_templates/'.($templateMap[$table]??'');
$templateStyles=''; $templateHeaderStyle=1; $templateTitleStyle=0; $templateSubtitleStyle=0; $templateDateStyle=0;
if($templateFile && is_file($templateFile) && class_exists('ZipArchive')){
  $tz=new ZipArchive(); if($tz->open($templateFile)===true){
    $templateStyles=(string)$tz->getFromName('xl/styles.xml');
    $tx=(string)$tz->getFromName('xl/worksheets/sheet1.xml');
    if(preg_match('/<c[^>]*r="A1"[^>]*s="(\d+)"/',$tx,$m))$templateTitleStyle=(int)$m[1];
    if(preg_match('/<c[^>]*r="A2"[^>]*s="(\d+)"/',$tx,$m))$templateSubtitleStyle=(int)$m[1];
    if(preg_match('/<c[^>]*r="A3"[^>]*s="(\d+)"/',$tx,$m))$templateDateStyle=(int)$m[1];
    if(preg_match('/<row r="5"[\s\S]*?<c[^>]*r="A5"[^>]*s="(\d+)"/',$tx,$m))$templateHeaderStyle=(int)$m[1];
    $tz->close();
  }
}
$filename='hospital_'.$table.'_'.date('Ymd_His').'.xlsx';
if(!class_exists('ZipArchive')){
  header('Content-Type: application/vnd.ms-excel; charset=utf-8');
  header('Content-Disposition: attachment; filename="'.str_replace('.xlsx','.xls',$filename).'"');
  echo "<html><head><meta charset='utf-8'></head><body>";
  echo '<h2>Hospital Management System - '.htmlspecialchars($table).'</h2><table border="1"><tr>';
  foreach($fields as $f) echo '<th>'.htmlspecialchars($f->name).'</th>'; echo '</tr>';
  foreach($rows as $row){echo '<tr>';foreach($fields as $f)echo '<td>'.htmlspecialchars((string)($row[$f->name]??'')).'</td>';echo '</tr>';}
  echo '</table></body></html>'; exit;
}

$tmp=tempnam(sys_get_temp_dir(),'hms_xlsx_'); unlink($tmp);
$zip=new ZipArchive(); $zip->open($tmp,ZipArchive::CREATE|ZipArchive::OVERWRITE);
$hasImage=false; $imageExt=''; $imageType='';
if($logoFile){
  $mime=(string)(mime_content_type($logoFile)?:'');
  $map=['image/png'=>['png','image/png'],'image/jpeg'=>['jpeg','image/jpeg'],'image/jpg'=>['jpeg','image/jpeg']];
  if(isset($map[$mime])){$imageExt=$map[$mime][0];$imageType=$map[$mime][1];$hasImage=true;$zip->addFile($logoFile,'xl/media/logo.'.$imageExt);}
}

$contentTypes='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
'<Default Extension="xml" ContentType="application/xml"/>'.
($hasImage?'<Default Extension="'.$imageExt.'" ContentType="'.$imageType.'"/>':'').
'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'.
'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'.
'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'.
($hasImage?'<Override PartName="/xl/drawings/drawing1.xml" ContentType="application/vnd.openxmlformats-officedocument.drawing+xml"/>':'').
'</Types>';
$zip->addFromString('[Content_Types].xml',$contentTypes);
$zip->addFromString('_rels/.rels','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
$zip->addFromString('xl/workbook.xml','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="'.xml(substr($table,0,31)).'" sheetId="1" r:id="rId1"/></sheets></workbook>');
$zip->addFromString('xl/_rels/workbook.xml.rels','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
if($templateStyles!=='') $zip->addFromString('xl/styles.xml',$templateStyles); else $zip->addFromString('xl/styles.xml','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="solid"><fgColor rgb="163B67"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/><xf numFmtId="0" fontId="1" fillId="1" borderId="0" applyFont="1" applyFill="1"><alignment horizontal="center"/></xf></cellXfs></styleSheet>');

$cols=count($fields); $sheet='<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheetViews><sheetView workbookViewId="0"/></sheetViews><sheetFormatPr defaultRowHeight="20"/><cols>';
for($i=1;$i<=$cols;$i++) $sheet.='<col min="'.$i.'" max="'.$i.'" width="22" customWidth="1"/>';
$sheet.='</cols>';
$rowNo=1;
$lastCol=colName(max(1,$cols));
$sheet.='<mergeCells count="3"><mergeCell ref="A1:'.$lastCol.'1"/><mergeCell ref="A2:'.$lastCol.'2"/><mergeCell ref="A3:'.$lastCol.'3"/></mergeCells><sheetData>';
$sheet.='<row r="1" ht="30"><c r="A1" s="'.$templateTitleStyle.'" t="inlineStr"><is><t>'.xml('HOSPITAL MANAGEMENT SYSTEM').'</t></is></c></row>';
$sheet.='<row r="2" ht="24"><c r="A2" s="'.$templateSubtitleStyle.'" t="inlineStr"><is><t>'.xml(ucwords(str_replace('_',' ',$table)).' Report').'</t></is></c></row>';
$sheet.='<row r="3" ht="20"><c r="A3" s="'.$templateDateStyle.'" t="inlineStr"><is><t>'.xml('Generated: '.date('Y-m-d H:i:s')).'</t></is></c></row>';
$rowNo=5;
$sheet.='<row r="'.$rowNo.'">';foreach($fields as $i=>$f){$ref=colName($i+1).$rowNo;$sheet.='<c r="'.$ref.'" s="'.$templateHeaderStyle.'" t="inlineStr"><is><t>'.xml($f->name).'</t></is></c>';}$sheet.='</row>';$rowNo++;
foreach($rows as $row){$sheet.='<row r="'.$rowNo.'">';foreach($fields as $i=>$f){$v=(string)($row[$f->name]??'');$ref=colName($i+1).$rowNo;if($v!=='' && is_numeric($v) && !preg_match('/^0\d/',$v))$sheet.='<c r="'.$ref.'"><v>'.xml($v).'</v></c>';else $sheet.='<c r="'.$ref.'" t="inlineStr"><is><t>'.xml($v).'</t></is></c>';}$sheet.='</row>';$rowNo++;}
$sheet.='</sheetData>';
if($hasImage){$sheet.='<drawing r:id="rId1"/>';}
$sheet.='</worksheet>';
$zip->addFromString('xl/worksheets/sheet1.xml',$sheet);
if($hasImage){
  $zip->addFromString('xl/worksheets/_rels/sheet1.xml.rels','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/drawing" Target="../drawings/drawing1.xml"/></Relationships>');
  $zip->addFromString('xl/drawings/_rels/drawing1.xml.rels','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="../media/logo.'.$imageExt.'"/></Relationships>');
  $zip->addFromString('xl/drawings/drawing1.xml','<?xml version="1.0" encoding="UTF-8" standalone="yes"?><xdr:wsDr xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><xdr:twoCellAnchor><xdr:from><xdr:col>0</xdr:col><xdr:colOff>0</xdr:colOff><xdr:row>0</xdr:row><xdr:rowOff>0</xdr:rowOff></xdr:from><xdr:to><xdr:col>2</xdr:col><xdr:colOff>0</xdr:colOff><xdr:row>2</xdr:row><xdr:rowOff>0</xdr:rowOff></xdr:to><xdr:pic><xdr:nvPicPr><xdr:cNvPr id="1" name="Hospital Logo"/><xdr:cNvPicPr/></xdr:nvPicPr><xdr:blipFill><a:blip r:embed="rId1"/><a:stretch><a:fillRect/></a:stretch></xdr:blipFill><xdr:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="1800000" cy="900000"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></xdr:spPr></xdr:pic><xdr:clientData/></xdr:twoCellAnchor></xdr:wsDr>');
}
$zip->close();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');header('Content-Disposition: attachment; filename="'.$filename.'"');header('Content-Length: '.filesize($tmp));readfile($tmp);unlink($tmp);exit;
