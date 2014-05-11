<?php

//Exam part B IMSE 7420
// a) Model and solve the following problem as a linear program using PHP and os.php
require_once 'Work-Cell-Scheduler/WCS/os.php';

function assertEquals($expected,$result) {
	if(!($expected===$result)){
		$message="assertEquasl: |$expected|$result|\n";
		throw new Exception($message);
	}
}
function assertNotEquals($expected,$result) {
	if(($expected===$result)){
		$message="assertNoeEquals: |$expected|$result|\n";
		throw new Exception($message);
	}
}
//------------------------------------------------------------------------------
//Number of Stores and Suppliers 
$numDepartments=3;
$numSuppliers=3;
$numCostIndexes=$numDepartments*$numSuppliers;

$departments=array();
$suppliers=array();

for($i=0;$i<($numDepartments);$i++){
	$departments[]="D-$i";
}
for($i=0;$i<($numSuppliers);$i++){
	$suppliers[]="S-$i";
}
print_r($departments);
print_r($suppliers);

//Supply and Demand Arrays
$capacity=array(600,300,200);
$demand=array(600,200,300);
$profitDisplay=array(20,30,40);
$profit=array(20,30,40,20,30,40,20,30,40);
$cost=array(2,3,3,5,2,4,3,3,8);
$actualProfit=array();

//Create Actual Profit Array
foreach ($cost as $key => $value) {
	$actualProfit[$key] = $profit[$key] - $cost[$key];
}
echo"\n";
echo "ActualProfit";
print_r($actualProfit);

$actualProfit1=array();
foreach($suppliers as $key=>$s){
	foreach($departments as $key=>$d){
		$actualProfit1["{$s}_{$d}"] = $actualProfit[$key];
	}
}
print_r($actualProfit1);

//Create Indexed Array for Supply Capacity
$supplyVal=array();
for($i=0;$i<($numSuppliers);$i++){
	$supplyVal["S-$i"]=$capacity[$i];
}
echo "SupplyVal";
print_r($supplyVal);

//Created Indexed Array for Store Demand
$demandVal=array();
for($i=0;$i<($numDepartments);$i++){
	$demandVal["D-$i"]=$demand[$i];
}
echo "demandVal";
print_r($demandVal);

//Create Decision Variable
$dvariable=array();
foreach($suppliers as $s){
	foreach($departments as $d){
		$dvariable[]="{$s}_{$d}";
	}
}
print_r($dvariable);

echo"\n". "Try indexed array for profit";

//$indexProfit=array();
//foreach ($dvariable as $dv){
//	$indexProfit[$dv]=0;
//}
//print_r($indexProfit);

//--------------------------------------------------------------------------
//Create OSIL file

$os=new WEBIS\OS;

foreach($dvariable as $dv){
	$v=$os->addVariable("$dv");
	$os->addConstraintCoef("$dv", $actualProfit1[$dv]);
}
echo "Check variable";
print_r($os);
?>

<?php
//Create HTML File
//---------------------------------------------------------------
?>

<html>
<meta charset="UTF-8">
<title>Decision Support Systems IMSE 7420 Final</title>
</head>
<body>
<h1>Decision Support Systems IMSE 7420 Final (E1)</h1>
<h2> Supply Data</h2>
<table border='1'>
<tr><td>Supplier</td>
<?php 
foreach ($suppliers as $s){
	echo"<td>$s</td>";
	}
echo"<tr><td>Supply</td>";
foreach($supplyVal as $s){
	echo"<td>$s</td>";
	}
echo"<tr>";
?>
</tr>
</table>
<h2> Demand Data</h2>
<table border='1'>
<tr><td>Department</td>
<?php 
foreach ($departments as $d){
	echo"<td>$d</td>";
	}
echo"<tr><td>Demand</td>";
foreach($demandVal as $d){
	echo"<td>$d</td>";
	}
echo"<tr>";
?>
</tr>
</table>
<h2> Profit Data</h2>
<table border='1'>
<tr><td>Department</td>
<?php 
foreach ($departments as $d){
	echo"<td>$d</td>";
	}
echo"<tr><td>Profit</td>";
foreach($profitDisplay as $p){
	echo"<td>$p</td>";
	}
echo"<tr>";
?>
</tr>
</table>

<h2> Profit-Shipping Data</h2>
<table border='1'>
<tr><td>Supplier to Department</td>
<?php 
foreach ($dvariable as $d){
	echo"<td>$d</td>";
	}
echo"<tr><td>Actual Profit</td>";
foreach($actualProfit as $p){
	echo"<td>$p</td>";
	}
echo"<tr>";
?>
</tr>
</table>
<h2> OSIL OSRL Optimization Solution</h2>
</body>
</html>









