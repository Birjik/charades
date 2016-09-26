<?php
	function swap(&$x,&$y){
		$z=$y;
		$y=$x;
		$x=$z;
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");                                           	                               			
	if(isset($_GET["lit"])){
		$result = $m -> query("SELECT * FROM `archive`");
		while(($row = $result->fetch_assoc()) != false){
		    if($row['status'] == 'running'){
		        $id = $row['id'];
		    	$table = $id."_teams";
		    	break;
		    }
		}
		$result = $m -> query("SELECT * FROM `$table`");
		$n = $result -> num_rows;
		$text = file_get_contents("../style/need.txt") + 1;
		if($text == $n+1)$text = 1;
		file_put_contents("../style/need.txt",$text);
		file_put_contents("../style/left.txt",-1);
		$ID = array();
		$TAKE = array();
		$len=0;
		$table=$id."_1";
		$result = $m -> query("SELECT * FROM `$table`");
		while(($row = $result->fetch_assoc()) != false){
			$ID[++$len] = $row['id'];
			$TAKE[$len] = $row['take'];
		}
		for($i = 1;$i < $len;$i ++)
		for($j = $i + 1;$j <= $len;$j ++){
			if($ID[$i] > $ID[$j]){
				swap($ID[$i],$ID[$j]);
				swap($TAKE[$i],$TAKE[$j]);
			}
		}
		for($i = 1;$i <= $len;$i ++){
			if($TAKE[$i] == ""){
			    $id = $ID[$i];
		   		$m -> query("UPDATE `$table` SET `take`='{[]}' WHERE `id`='$id'");
		   		break;			   	       						
			}
		}    
	?>
		<script type="text/javascript">   	
			document.location.href = "http://www.crocodile.kz/a.php";
			document.write(docment.location.href);
		</script>
	<?
	exit;
	}
	if(isset($_GET["play_time"])){
	    $id = "";
	    $ok = 1;
        foreach($_POST as $key => $value){
       		for($i = 1;$i < strlen($key);++ $i)$id .= $key[$i];
        	if($key[0] == 'a')$ok = 0;
       	}
		$result = $m -> query("SELECT * FROM `archive`");
		while(($row = $result->fetch_assoc()) != false){
		    if($row['status'] == 'running'){
		    	$table = $row['id']."_1";
		    	break;
		    }
		}
   		$text = file_get_contents("../style/need.txt");
   		$now = "";
   		for($i = 0;$i < strlen($text);++ $i){
   			if($text[$i] == ' ')break;
   			$now.=$text[$i];
   		}
       	if($ok == 0){
	   		$m -> query("UPDATE `$table` SET `take`='$now' WHERE `id`='$id'");			   	       		
       	}
       	else {
       		$left = file_get_contents("../style/left.txt");
       		$left--;
       		$left = file_put_contents("../style/left.txt",$left);
	   		$m -> query("UPDATE `$table` SET `take`='x_$now' WHERE `id`='$id'");			   	       		
       	}
		header("Location:http://www.crocodile.kz/1.php");
		exit;
	}
	if(isset($_POST["next"])){
	    $text = file_get_contents("../style/need.txt");
	    $new = "";
	    $ok = 0;
	    for($i = 0;$i < strlen($text);++ $i){
	    	if($text[$i] == " "){
	    		$ok = 1;
				continue;
	    	}
	    	if($ok == 0)$new .= $text[$i];
	    	else {
	    		$new .= " ".time();
	    		break;
	    	}
	    }
	    file_put_contents("../style/need.txt",$new);
	    file_put_contents("../style/left.txt",2);
		header("Location:http://www.crocodile.kz/1.php");
		exit;
	}
	if(isset($_GET["id"])){
        $id = $_GET["id"];
        $pg = $_GET["page"];                                                        	                               			
		$table = $id."_1";                                                            				
		$result = $m -> query("SELECT * FROM `$table`");                             				
		$n = $result -> num_rows;
        foreach($_POST as $key => $value){
        	if($value == '.'){
    	  			$m -> query("DELETE from `$table` WHERE `id`='$key'");
    	  			while($key < $n){
    	  			    $nx = $key + 1;
		   	 	  	$m -> query("UPDATE `$table` SET `id`='$key' WHERE `id`='$nx'");			   	
		   	 	  	$key++;
    	  			}
    	  		}
        	else {
        		$ok=true;
				$result = $m -> query("SELECT * FROM `$table`");
			    while ($row = $result->fetch_assoc()){                     
					if($row['word']==$value)
						$ok=false;
				}
    	 	  	if($ok==true)$m -> query("UPDATE `$table` SET `word`='$value' WHERE `id`='$key'");			   	
        	}
        }
		header("Location:http://www.crocodile.kz/lib/show.php?page=$pg");
		exit;
	}
	if(isset($_GET["game"])){
		$id = $_GET["game"];
	    $tab=$id."_2";
    	$result = $m -> query("SELECT * FROM `$tab`");
		$i = 0;
		$table = array();
		for($i = 1;$i <= 4;++ $i)$table[i] = array();
		for($i = 1;$i <= 4;++ $i)
			for($j = 0;$j <= 4;++ $j)
				$table[$i][$j] = "{[]}";
		while(($row = $result->fetch_assoc()) != false){
			$i++;
			$table[$row['type']][$row['point']/10] = $row['word'];
		}
		for($i = 1;$i <= 4;++ $i){
			for($j = 0;$j <= 4;++ $j){
				$c = $i.$j;
				$c = $_POST[$c];
				$p = $j * 10;
   	 	 	  	if($table[$i][$j] == "{[]}")$m->query("INSERT INTO `$tab` (`type`,`word`,`point`) VALUES ('$i','$c','$p')");
   	 	 		else $m -> query("UPDATE `$tab` SET `word`='$c' WHERE `type`='$i' AND `point`='$p'");			   	
			}
		}
		header("Location:http://www.crocodile.kz/add2.php");
		exit;
	}
	if(isset($_GET["add"])){
	  	$m = new mysqli("localhost","root","","archive_crocodile");			
		$m -> query("SET NAMES 'cp1251'");
		$id = $_GET["add"];
		$table = $id."_teams";
		$result = $m -> query("SELECT * FROM `$table`");
		$n = $result -> num_rows;
        foreach($_POST as $key => $value){
        	if($value == '√отово'){
				$m -> query("UPDATE `archive` SET `cur`='1' WHERE `status`='running'");			   	
				header("Location:http://www.crocodile.kz/1.php");				
        		exit;
        	}                   
        	if($value == '.'){
        		$tbl = $table."_".$key;
       			$m -> query("DROP TABLE $tbl");  
       			$cp = $key;
    	  		while($cp < $n){
    	  		    $nx = $cp + 1;
	        		$tblt = $table."_".$cp;
	        		$tblf = $table."_".$nx;
		   			$m -> query("RENAME TABLE  `archive_crocodile`.`$tblf` TO  `archive_crocodile`.`$tblt`");
			   	 	$cp++;
    	  		}
    	  		$m -> query("DELETE from `$table` WHERE `id`='$key'");
    	  		$place = $key - 1;
    	  		while($key < $n){
    	  		    $nx = $key + 1;
			   	 	$m -> query("UPDATE `$table` SET `id`='$key' WHERE `id`='$nx'");			   	
			   	 	$key++;
    	  		}
				header("Location:http://www.crocodile.kz/registration.php#need$place");
        		exit;
        	}
        	else if($value == ' '){
         	    $word = "word".$key;
        	    $word = $_POST[$word];
        		$ok=true;
				$result = $m -> query("SELECT * FROM `$table`");
			    while ($row = $result->fetch_assoc()){                     
					if($row['name']==$value)
						$ok=false;
				}
				if($word == "{[]}")$ok = 0;
    	  	 	if($ok==true)$m -> query("UPDATE `$table` SET `name`='$word' WHERE `id`='$key'");
				header("Location:http://www.crocodile.kz/registration.php#need$key");
        		exit;
        	}
        }
		$cnt = 1;
		$name = "Team #1";
		for($cnt = 1;$cnt <= $result -> num_rows;$cnt ++){
		    $ok = 0;
			$result = $m -> query("SELECT * FROM `$table`");
			while(($row = $result->fetch_assoc()) != false){
				if($row['name'] == "Team #".$cnt){
				    $ok = 1;
					break;
				}			
			}
			if($ok == 0){
				$name = "Team #".$cnt;
				break;
			}
			$ok = -1;
		}
		if($ok == -1)$name = "Team #".$cnt;
		$cnt = $result -> num_rows+1;
        $m->query("INSERT INTO `$table` (`id`,`name`) VALUES ('$cnt','$name')");
        $table.="_".$cnt;
        $m->query("
        CREATE TABLE IF NOT EXISTS `$table` (
		  `id` int(11) unsigned NOT NULL,
		  `player` varchar(255) NOT NULL,
		  UNIQUE KEY `id` (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	 	$m -> close();
		header("Location:http://www.crocodile.kz/registration.php#need");
		exit;
	}
	if(isset($_GET['add_player'])){
	        $id = $_GET['add_player'];
	        $table = $id."_teams_";
	        foreach($_POST as $key => $value){
	        	$table.=$key;
       	        $team = $key;
	        }
		  	$m = new mysqli("localhost","root","","archive_crocodile");			
			$m -> query("SET NAMES 'cp1251'");
			$result = $m -> query("SELECT * FROM `$table`");
			$n = $result -> num_rows;
			$cnt = $n + 1;
	        $m->query("INSERT INTO `$table` (`id`,`player`) VALUES ('$cnt','')");
			header("Location:http://www.crocodile.kz/registration.php#need$team");
			exit;
	}
	if(isset($_GET['change_player'])){
	    $table = $_GET['change_player']."_teams_";
	    $id = "{[()]}";
	    $ms = "1";
        foreach($_POST as $key => $value){
            if($key == "must")
			    $ch = $value;
            else {
                $ok = 0;
            	$cp = "";
                if($value == ' ')$ms = "0";
				for($j = 0;$j < strlen($key);++ $j){
					if($key[$j] == '_'){
					    $ok = 1;
						$id = "";
						continue;
					}
				    if($ok == 0)$cp .= $key[$j];
					if($id == "{[()]}")$table.=$key[$j];
					else $id .= $key[$j]; 
				}
			}         
        }
	  	$m = new mysqli("localhost","root","","archive_crocodile");			
		$m -> query("SET NAMES 'cp1251'");
        if($ms == 0){
   	   		$m -> query("UPDATE `$table` SET `player`='$ch' WHERE `id`='$id'");
        }
        else {
			$result = $m -> query("SELECT * FROM `$table`");
			$n = $result -> num_rows;
  	  		$m -> query("DELETE from `$table` WHERE `id`='$id'");        
  			while($id < $n){
  			    $nx = $id + 1;
	  	 	  	$m -> query("UPDATE `$table` SET `id`='$id' WHERE `id`='$nx'");			   	
  	 	  		$id++;
  	 	  	}
        }
  		$m -> close();			   	        
  		$id = $_GET['change_player'];
		header("Location:http://www.crocodile.kz/registration.php#need$cp");
		exit;
	}
	if(isset($_GET["end"])){
		if($_POST["ok"] == «Да»){
			header("Location:http://www.crocodile.kz/1.php");				
			$m -> query("UPDATE `archive` SET `cur`='1' WHERE `status`='running'");			   	
        	exit;
		}
		else {
		    $id = $_GET["end"];
			header("Location:http://www.crocodile.kz/registration.php");				
        	exit;			
		}
	}
	if(isset($_POST["endr"])){
		header("Location:http://www.crocodile.kz/1.php?next=1");				
		exit;
	}
	
?>
