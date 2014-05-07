<?php
//B4: Create training matrix class to hold worker/cell productivity
//SET
$productivity=array();
foreach($workers as $wo){
	foreach($cells as $ce){
		$productivity["{$wo}_{$ce}"]= rand(0,100)/100.0;
	}
}
//print_r($productivity);
//GET
function getProductivity($worker,$cell,$productivity){
	if (array_key_exists("{$worker}_{$cell}", $productivity)===FALSE){
		return 0.5;
	}
	return $productivity["{$worker}_{$cell}"];
}
$v = getProductivity("worker-1", "cell-1",$productivity);
//print_r($v);
//C: Optimization
//C0: Solve empty problem
require_once 'Work-Cell-Scheduler/WCS/os.php';
$a=new WebIS\OS();
$a->solve();
//print_r($a);
//C1: setup obj function, minimize worker hours on each cell
$obj=array();
foreach($workers as $wo){
	foreach($cells as $ce){
		$b=$a->addVariable("{$wo}_{$ce}");
		$a->addObjCoef("{$wo}_{$ce}", 1);
	}
}
print_r($a);
?>