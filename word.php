<?php
    $t = "";
	foreach($_POST as $key => $value)$t = $key;
	if($t == ""){
		header("Location:http://www.crocodile.kz/2.php");
    	exit;	
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `archive`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['status'] == 'running'){
	    	$id = $row['id'];
	     	$author = $row['author'];
	    	break;
	    }
	}
	$tb = $table = $id."_2";
	$result = $m -> query("SELECT * FROM `$table`");
   	$x = floor($t/10);
	$b = ($t%10)*10;
	while(($row = $result->fetch_assoc()) != false){
		if($row['type']==$x && $row['point'] == 0)
			$a = $row['word'];
		if($row['type']==$x && $row['point'] == $b)
			$wid = $row['word'];
	}
    $table = $id."_2"; 
	$result = $m -> query("SELECT * FROM `$table`");
	$cnt1 = 0;
	while(($row = $result->fetch_assoc()) != false){
	    if($row['take'] != "" && $row['word'] != $wid)
	    	++$cnt1;
	}
	$table = $id."_teams";
	$result = $m -> query("SELECT * FROM `$table`");
	$n = $result -> num_rows;
    if($_GET["pf"]){
		$cur = $cnt1 % $n;
		if($cur == 0)$cur = $n;
	    $table = $id."_2"; 
    	$x = floor($_GET["pf"]/10);
    	$b = ($_GET["pf"]%10)*10;
		$m -> query("UPDATE `$table` SET `take`='$cur' WHERE `type`='$x' AND `point`='$b'");			   	
		header("Location:http://www.crocodile.kz/a.php?change=1");
    	exit;
    }
	$cnt1++;
	$cur = $cnt1 % $n;
	if($cur == 0)$cur = $n;
	$table = $id."_teams";      
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['id'] == $cur){
			$name = $row['name'];
	    	break;
	    }
	}
?>
<script type="text/javascript">
	function setCookie(name, value){
		document.cookie = name + "=" + value;
	}
	function getCookie(name){
		var r = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
		if(r) return r[2];
		else return "";
	}
	if(getCookie("time") == ""){
		var now = new Date();
		var tm = now.getTime();
		setCookie("time",tm);
	}
	function timer(){
		var cur = new Date();
		if(getCookie("time") != ""){
			var s = Number(document.getElementById("timer").innerHTML);
			if(s == "0.0")s = "1.0";
			if(s <= 0){
			}
			else {
				var cur = new Date();
				if(30-(Math.floor( (cur.getTime() - getCookie("time")) / 1000)) < 0){
				    <?php
			    		$m -> query("UPDATE `$tb` SET `take`='x$cur' WHERE `type`='$x' AND `point`='$b'");			   	
				    ?>
					document.location.href = "http://www.crocodile.kz/a.php?change=1";
					document.write(docment.location.href);				
				}

				document.getElementById("timer").innerHTML = 30-(Math.floor( (cur.getTime() - getCookie("time")) / 1000));
				setTimeout("timer()", 1000);
			}
		}
	}		
</script>
<html>
	<head>
		<title> Крокодил</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>
	<body onload="timer()">
		<div id="all">
			<div id="header">
			    <h1>
    				<a href="#add_word">                               			    
				    	 Крокодил
    				</a>
			    </h1>
			</div>
			<div id="content">
				<div id="finish">
				<span id="str">
				Автор: <?php echo $author;?>
				</span>
		        </div>
				<span id="str">Тур 2 (Игра  #<?php echo $id;?>)</span>
		    	<hr>
		    	<div id="center">
					<span style="color:green; font-size:175%"><?php echo $name;?></span>
					<br>
					<br><br>
					<span style="font-size:200%"><i><?php echo $a;?> </i>
					<?php 
						if($b == 10)echo "10";
						if($b == 20)echo "20";
						if($b == 30)echo "30";
						if($b == 40)echo "40";
					?>    
					</span>
					<div id="show_word2">
					<?php 
					    $words = array();
					    $ln = 0;
						for($i=0;$i<strlen($wid);++$i){
						    if($wid[$i] == " "){
						    	++$ln;
						    	continue;
						    }
							$words[$ln] .= $wid[$i];
						}
						$cnt = 0;
						for($i = 0;$i <= $ln;++ $i){
						    if(strlen($words[$i]) == 0)continue;
							if($cnt + strlen($words[$i]) > 18){$cnt = 0;echo "<br>";}
							echo $words[$i]." ";
						    $cnt += strlen($words[$i]) + 1;
						}
					?>
					</div>
					<div id="add_word">
						<form action="word.php?pf=<?php echo $x.$b/10;?>" method="post">
							<input name="ok" type="submit" value=«Правильно">
						</form>
					</div>
					<div id="time2"> 
						Осталось<br>
						<span id="timer">
							0.0
						</span><br>
						секунд
					</div>
			</div>
			</div>
		</div>
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>
