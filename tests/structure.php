<?php
// Mirrors the documented Symcon 9.0 library discovery rule, without loading device instances.
error_reporting(E_ALL);
set_error_handler(function($severity,$message,$file,$line){throw new ErrorException($message,0,$severity,$file,$line);});
$base=dirname(__DIR__);$checks=0;
function check($test,$message){$GLOBALS['checks']++;if(!$test)throw new RuntimeException($message);}
function decoded($path){return json_decode(file_get_contents($path),true,512,JSON_THROW_ON_ERROR);}
$library=decoded($base.'/library.json');check($library['version']==='2.0.1'&&$library['build']===4,'Expected 2.0.1 / Build 4');
$reserved=['libs','docs','imgs','tests','actions'];$modules=[];
foreach(glob($base.'/*',GLOB_ONLYDIR) as $folder){
    if(in_array(basename($folder),$reserved,true))continue;
    check(is_file($folder.'/module.json'),'Invalid top-level directory '.basename($folder).': module.json missing');
    check(is_file($folder.'/module.php'),'module.php missing');
    $meta=decoded($folder.'/module.json');
    check(!isset($modules[$meta['id']]),'Duplicate module ID');
    $modules[$meta['id']]=$meta;
    check(preg_match('/class\s+'.preg_quote(str_replace(' ','',$meta['name']),'/').'\s+extends\s+IPSModule\b/',file_get_contents($folder.'/module.php'))===1,'Module name and PHP class must agree');
    decoded($folder.'/form.json');$checks++;
    preg_match_all("~require_once __DIR__ \\. '([^']+)'~",file_get_contents($folder.'/module.php'),$includes);
    foreach($includes[1] as $relative)check(is_file($folder.$relative),'Missing included file '.$relative);
}
check(count($modules)===2,'Only the two actual modules may be discovered');
foreach(['{07E9D048-9A26-4F61-8E32-6E45CCD21BF1}','{806558EC-EE9D-472D-B71E-A2916ADC6353}'] as $id)check(isset($modules[$id]),'Existing module ID must be preserved');
check(!is_dir($base.'/shared'),'The obsolete shared directory must be deleted');
foreach(['Widget.php','OtherRooms.php','app.js','renderer.js','style.css','module.html','assets/panorama.jpg'] as $file)check(is_file($base.'/libs/'.$file),'Missing common file '.$file);
check(strpos(file_get_contents($base.'/WohnbereichTile/module.php'),'../shared/')===false,'Old include path in main module');
check(strpos(file_get_contents($base.'/WohnbereichOverview/module.php'),'../shared/')===false,'Old include path in overview');
echo 'PASS '.$checks.' library structure / metadata / include checks.'.PHP_EOL;
