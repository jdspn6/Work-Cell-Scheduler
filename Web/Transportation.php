<?php
// Optimization Services Test Copyright 2014 by WebIS Spring 2014 License Apache 2.0
require_once 'Work-Cell-Scheduler/TDD/validator.php';
require_once 'Work-Cell-Scheduler/WCS/os.php';
include 'Work-Cell-Scheduler/Config/local.php';

	function SolveTranspo(){
		$os=New WebIS\OS;
		
		//Variables
		$os->addVariable('x11');
		$os->addObjCoef('x11', '3');
		$os->addVariable('x12');
		$os->addObjCoef('x12', '2');
		$os->addVariable('x21');
		$os->addObjCoef('x21', '1');
		$os->addVariable('x22');
		$os->addObjCoef('x22', '5');
		$os->addVariable('x31');
		$os->addObjCoef('x31', '5');
		$os->addVariable('x32');
		$os->addObjCoef('x32', '4');
		
		// Constraints
		$os->addConstraint(45);
		$os->addConstraintCoef('x11',1);
		$os->addConstraintCoef('x12',1);
		
		$os->addConstraint(60);
		$os->addConstraintCoef('x21',1);
		$os->addConstraintCoef('x22',1);
		
		$os->addConstraint(5);
		$os->addConstraintCoef('x31',1);
		$os->addConstraintCoef('x32',1);
		
		$os->addConstraint(null,50);
		$os->addConstraintCoef('x11',1);
		$os->addConstraintCoef('x21',1);
		$os->addConstraintCoef('x31',1);
		
		$os->addConstraint(null=60);
		$os->addConstraintCoef('x12',1);
		$os->addConstraintCoef('x22',1);
		$os->addConstraintCoef('x32',1);
		
		print_r($os);
		
		$os->solve();
		
	}

SolveTranspo();

?>