<?php
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `3_1`");
	while(($row = $result->fetch_assoc()) != false){
	    $id = $row['id'];
   		$m -> query("UPDATE `3_1` SET `take`='' WHERE `id`='$id'");			   	       		
	}
	$m -> close();
	exit;
?>