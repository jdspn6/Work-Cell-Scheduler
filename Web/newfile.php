<?php
//A: Model and Solve the Linear Program using php and os.php
$capacity=array(600,300,200); //(s0,s1,s2)
$demand=array(600,200,300); //(d0,d1,d2)
$profit=array(20,30,40); //(d0,d1,d2)
$cost=array(2,3,3,5,2,4,3,2,8); //(s0d0,s0d1,s0d2,s1d0,s1d1,s1d2,s2d0,s2d1,s2d2)
$numSupply=3;
$numDepartments=3;
$supply=array();
$departments=array();
for($i = 0; $i < $numSupply; $i++) {
	$supply[] = "s-$i";
}
for($i = 0; $i < $numDepartments; $i++) {
	$departments[] = "d-$i";
}
//create decision variable
$dvariable=array();
foreach($supply as $s){
	foreach ($departments as $d){
		$dvariable[]="{$s}_{$d}";
	}
}
//print_r($variable)
//create array that assosiates capacities with supply
$capacityconst[$s]=array();
foreach ($supply as $s){
	$capacityconst[$s]=0;
}
foreach ($supply as $key=>$s){
	$capacityconst[$s]=$capacity[$key];
}
//print_r($capacityconst);
//create array that associates transportation cost with decision variables
$cost1=array();
foreach($dvariable as $key=>$dv) {
	$cost1[$dv]= $cost[$key];
}
//print_r($cost1);
//create array that associates profit with each department
$profitList=array();
foreach($departments as $key=>$d) {
	$profitList[$d]= $profit[$key];
}
//print_r($profitList);
//assign the correct profit to each decision variable and calculate objective coefficients
$prof=array();
foreach($supply as $s){
	foreach($departments as $d){
		$prof["{$s}_{$d}"]=$profitList[$d];
	}
}
//print_r($prof);
$oFCoef=array();
foreach($supply as $s){
	foreach($departments as $d){
		$oFCoef["{$s}_{$d}"]=$prof["{$s}_{$d}"]-$cost1["{$s}_{$d}"];
	}
}
//print_r($oFCoef);
　
　//Solve empty problem
　require_once 'Work-Cell-Scheduler/WCS/os.php';
　$a=new WebIS\OS();
　$a->solve();
　//print_r($a);
　//Setup obj function
　foreach($dvariable as $dv){
　$b=$a->addVariable("$dv");
　$a->addObjCoef("$dv", $oFCoef[$dv]);
}
//print_r($a);
//Generate Capacity Constraint
foreach ($supply as $s){
$a->addConstraint($capacityconst[$s],NULL);
foreach ($departments as $d){
$a->addConstraintCoef("{$s}_{$d}", 1);
}
}
//Generate Demand Constraint
foreach($departments as $key=>$d){
$a->addConstraint(NULL,$demand[$key]);
foreach ($supply as $s){
$a->addConstraintCoef("{$s}_{$d}", 1);
}
}
echo $a->solve();