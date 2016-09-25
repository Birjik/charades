<?php
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `3_2`");
	while(($row = $result->fetch_assoc()) != false){
	    $p = $row['point'];
	    $t = $row['type'];
   		$m -> query("UPDATE `3_2` SET `take`='' WHERE `point`='$p' AND `type`='$t'");			   	       		
	}
	$m -> close();
	exit;
?>