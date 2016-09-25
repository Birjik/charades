<?php
    function ok($str){
    	for($l = 0;$l < strlen($str);++ $l){
    		if('0' <= $str[$l] && $str[$l] <= '9')return true;
    		if('À' <= $str[$l] && $str[$l] <= 'ß')return true;
    		if('à' <= $str[$l] && $str[$l] <= 'ÿ')return true;
    		if('A' <= $str[$l] && $str[$l] <= 'Z')return true;
    		if('a' <= $str[$l] && $str[$l] <= 'z')return true;
    		if($str[$l] == '³')return true;
    	}
    	return false;
    }
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
    $id = $_GET["id"];
	$table = $id."_1";
	$result = $m -> query("SELECT * FROM `$table`");                             					
	$n = $result -> num_rows;
	if($n < 30){
		header("Location:http://www.crocodile.kz/add2.php?problem=1");
		exit;
	}
	$table = $id."_2";
	$result = $m -> query("SELECT * FROM `$table`");                             					
	for($i = 1;$i <= 4;++ $i)
		for($j = 0;$j <= 4;++ $j)
			$tab[$i][$j] = "{[]}";
	while(($row = $result->fetch_assoc()) != false){
		$i++;
		$tab[$row['type']][$row['point']/10] = $row['word'];
	}
	for($i = 1;$i <= 4;++ $i)
		for($j = 0;$j <= 4;++ $j){
			if(!ok($tab[$i][$j])){
				$c = $i.$j;
				header("Location:http://www.crocodile.kz/add2.php?problem=2");
				exit;
			}
		}
	header("Location:http://www.crocodile.kz/add2.php?problem=0");
?>
