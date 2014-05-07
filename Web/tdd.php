<?php
// Optimization Services Test Copyright 2014 by WebIS Spring 2014 License Apache 2.0

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

//B1: Setup Problem and data structures

$numWorkers=5;
$numCells=4;
$numProducts=6;

$workers=array();
$cells=array();
$products=array();

//echo "WorkerTest>\n";
for($i=0;$i<($numWorkers);$i++){
	$workers[]="Worker-$i";
}
//print_r($workers);

//echo "CellTest>\n";
for($i=0;$i<($numCells);$i++){
	$cells[]="Cell-$i";
}
//print_r($cells);


//echo "ProductTest>\n";
for($i=0;$i<($numProducts);$i++){
	$products[]="Product-$i";
}
//print_r($products);

//B2: Pick Random Worker and Test Using TDD
assertEquals($workers[1],'Worker-1');

//B3: Create Demand using a structure/class that holds (products, cell, hours)

class Demand {
	
	Public $product;
	Public $cell;
	Public $hours;
	
	function __construct($p,$c,$h){
	
		$this->product=$p;
		$this->cell=$c;
		$this->hours=$h;
		}
	}
	
$numberofRandom=20;

for($i=0;$i<($numberofRandom);$i++){	
	$demandList[$i]=new Demand($products[array_rand($products,1)], $cells[array_rand($cells,1)], rand(1,3));
}

//print_r($demandList);

//B4: Create Training Matrix class to hold worker/cell productivity

$productivity=array();
foreach($workers as $w){
	foreach($cells as $c){
		$productivity["${w}_${c}"]=rand(80, 100)/100.0;
	}
}
//print_r($productivity);

function getProductivity($w, $c, $productivity){
	If (array_key_exists("${w}_${c}",$productivity)===False){
		return 0.5;
	}
	return $productivity["${w}_${c}"];
}

$v=getproductivity("Worker-0","Cell-0",$productivity);
//print_r($v);

echo "\n";
echo "\n";

//C:optimization 
// C0:Solve Empty Problem

$a=new WEBIS\OS;

//C1: setup objective function, minimize worker hours on each cell

foreach ($workers as $w){
	foreach($cells as $c){
		$b=$a->addVariable("${w}_${c}");
		$c=$a->addObjCoef("${w}_${c}",1);
	}
}

//C2:build production requirment
//How much work is required on each cell

$cellRequirements=array();
foreach ($cells as $cell){
	$cellRequirements[$cell]=0;
}
//print_r($cellRequirements);

foreach($demandList as $demand){
	//print_r($demand);
	$cellRequirements[$demand->cell]+=$demand->hours;
}
// print_r($cellRequirements);
 

 //Calculated total production hours (used to check later)
 
 $sum=0;
 foreach($cellRequirements as $s){
 	$sum=$sum + $s;
 }
 echo"\n";
 echo"\n";
// print_r($cellRequirements);
 
 
 //C3:generate constraints off this (not OS changed to $ub, $lb and made required) and solve
 
foreach($cellRequirements as $key=>$cr){
 	$constraint=$a->addConstraint(NULL,$cr);
		foreach($workers as $w){
 		$a->addConstraintCoef("{$w}_{$key}", $productivity["{$w}_{$key}"]);
		}
}

 //C4:Check that the total hours allocated is at least the total requested
//print_r($sum);
echo"\n";
 //C5: Cap worker hours to 8 a day
 	foreach($workers as $w){
 		$a->addConstraint(NULL,8);
 		foreach($cells as $c){
 			$a->addConstraintCoef("{$w}_{$c}",1);
 		}
 	}
 //print_r($a);
 

//assert($a->solve()>=$sum);
echo "\n";
//$solution=array();
//$solution=$a->value;
$objvalue=$a->getSolution();

 //Create HTML Page
echo $a->solve();
 ?>

<html>
<meta charset="UTF-8">
<title>Worker Productivity Optimization Solution</title>
</head>
<body>
<h1>Worker Productivity Optimization Solution</h1>
<br>
<table border='1'>
<tr><td>Objective Value</td></tr>
<?php 
echo "<tr><td>$objvalue </td></tr>";
?>
</table>

<br>
<table border='1'>
<tr><th></th>
<?php 
foreach($cells as $c){
	echo"<th>$c</th>";
	}
foreach($workers as $w){
	echo "<tr><td> $w </td</tr>";
	foreach($cells as $c){
		echo"<td>".$a->getVariable("{$w}_{$c}")."</td>";
	}
}


?>
</tr>
</table>
</body>
</html>
	
 
