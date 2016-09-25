<?php
	$left = file_get_contents("style/left.txt");
	function swap(&$x,&$y){
		$z=$y;
		$y=$x;
		$x=$z;
	}
	$need = file_get_contents("style/need.txt");
	if($need[0] == 'x'){
		$cur = 1;	
		file_put_contents("style/need.txt",$cur);
	}
	else {
	    $cur = $need;
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	if(isset($_POST["END"])){
		setcookie("timer",time()-3600);
		file_put_contents("style/need.txt",1);
		$m -> query("UPDATE `archive` SET `cur`='res1' WHERE `status`='running'");			   	
		$m -> close();
?>
<script type="text/javascript">
	function deleteCookie(name){
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString(); 
	}
	deleteCookie("timer");
	document.location.href = "http://www.crocodile.kz/res1.php";
	document.write(docment.location.href);
</script>
<?php	} 
	if(isset($_GET["next"])){
?>		
	<div class = "message">
	        Вы уверены что хотите закончить игру?<br>
			<form action = "1.php" method = "post">
			 	<input type="submit" name="END" value="Да">
				<input type="submit" name="NO" value="Нет">
			</form>
	</div>			
<?php
}
	$result = $m -> query("SELECT * FROM `archive`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['status'] == 'running'){
	        $author = $row['author'];
	    	$id = $row['id'];
	        $mur = $row['cur'];
	    	break;
	    }
	}
	if($mur != "1"){
		echo "
		<html>               	                                                            			   	
	<head>                                                                       			   	
		<title>Крокодил</title>                                                  			   	
		<link rel='stylesheet' type='text/css' href='../style/style.css'>                 			   	
		<link rel='shortcut icon' href='images/favicon.ico'>
	</head>                                                                      			   	
	<body>                                                                       			   	
		<div id='all'>                                                           			   	
			<div id='header'>                                                    			    
			    <h1>                                                             			    
    				<a href='http://www.crocodile.kz'>                               			    
					    Крокодил                                                 			    
    				</a>                                                         			    
			    </h1>                                                            			    
			</div>                                                               			    
			<div id='content'>                                                   			    
				<h1 align='center' style='color:red'>Произошла ошибка!</h1>
			</div>
		</div>
		<div id='footer'>
			&copy; Ауганов Биржан 2014
		</div>
		</body>
	    </html>";	
		exit;	
	}
	$table = $id."_teams";
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['id'] == $cur){
			$name = $row['name'];
	    	break;
	    }
	}
	$ID = array();
	$WORD = array();
	$TAKE = array();
	$len = 0;
	$table = $id."_1";
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
		$ID[++$len] = $row['id'];
		$WORD[$len] = $row['word'];
		$TAKE[$len] = $row['take'];
	}
	for($i = 1;$i < $len;$i ++)
		for($j = $i + 1;$j <= $len;$j ++){
			if($ID[$i] > $ID[$j]){
				swap($ID[$i],$ID[$j]);
				swap($WORD[$i],$WORD[$j]);
				swap($TAKE[$i],$TAKE[$j]);
			}
		}
	$cnt = 0;
	$wid = "";
	for($i = 1;$i <= $len;$i ++){
		if($TAKE[$i] == ""){
		    if($wid == "")$wid = $ID[$i];
			++$cnt;
		}
	}
	if($cnt == 0){
		setcookie("timer",time()-3600);
		file_put_contents("style/need.txt",1);
		$m -> query("UPDATE `archive` SET `cur`='res1' WHERE `status`='running'");			   	
		$m -> close();
?>
<script type="text/javascript">
	function deleteCookie(name){
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString(); 
	}
	deleteCookie("timer");
	document.location.href = "http://www.crocodile.kz/res1.php";
	document.write(docment.location.href);
</script>
<?
	exit;
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
	function timer(){
		var cur = new Date();
		if(getCookie("timer") != ""){
			var s = Number(document.getElementById("timer").innerHTML);
			if(s == "0.0")s = "1.0";
			if(s <= 0){
				document.location.href = "http://www.crocodile.kz/lib/upd.php?lit=1";
				document.write(docment.location.href);
			}
			else {
				var cur = new Date();
				if(40-(Math.floor( (cur.getTime() - getCookie("timer")) / 1000)) < 0){
					document.location.href = "http://www.crocodile.kz/lib/upd.php?lit=2";
					document.write(docment.location.href);				
				}
				document.getElementById("timer").innerHTML = 40-(Math.floor( (cur.getTime() - getCookie("timer")) / 1000));
				setTimeout("timer()", 1000);
			}
		}
	}		
</script>
<html>
	<head>
		<title>Тур 1</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>
	<body onload="timer()">
		<div id="all">
			<div id="header">
			    <h1>
    				<a href="<?php 
    				if($left == -1)echo "http://www.crocodile.kz";
    				else "#";?>">
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
				<span id="str">Тур 1 (Игра №<?php echo $id;?>)</span>
				<hr>
				<div id="center">
				    <?php if($left == -1){?>
				    <br><br><br>				   
					<span style="color:grey; font-size:200%">Ожидание команды : <span style="color:green"><?php echo $name;?></span> (№<?php echo $cur;?>)</span>
				    <br><br><br>
					<span style="font-size:125%">Осталось <?php echo $cnt;?> слов.</span>
					<br><br><br>
					<div id="add_word">
						<form action="lib/upd.php" method="post">
							<input name="next" type="submit" value="Играть!">
							&nbsp;
							<input name="endr" type="submit" value="Закончить тур">
						</form>
					</div>
					<?php }
					else {?>
					<script type="text/javascript">
				    if(getCookie("timer") == ""){
				 		var now = new Date();
						var tm = now.getTime();
						setCookie("timer",tm);
	    			}
	    			</script>
					<span style="color:green; font-size:175%"><?php echo $name;?></span>
					<div id="show_word">
					<?php 
					    $words = array();
					    $ln = 0;
						for($i=0;$i<strlen($WORD[$wid]);++$i){
						    if($WORD[$wid][$i] == " "){
						    	++$ln;
						    	continue;
						    }
							$words[$ln] .= $WORD[$wid][$i];        
						}
						$cnt1 = 0;
						for($i = 0;$i <= $ln;++ $i){
						    if(strlen($words[$i]) == 0)continue;
							if($cnt1 + strlen($words[$i]) > 15){$cnt1 = 0;echo "<br>";}
							echo $words[$i]." ";
						    $cnt1 += strlen($words[$i]) + 1;
						}
					?>
					</div>
					<div id="add_word">
					<form action="lib/upd.php?play_time=1" method="post">
						<input name="a<?php echo $wid;?>" type="submit" value="Правильно">
						&nbsp;
						<?php
							$left = file_get_contents("style/left.txt");
							if($left == 0){
								echo "<span id='zero'>Следующий [0]</span>";													
							}
							else {
						?>
						<input name="b<?php echo $wid;?>" type="submit" value="Следующий [<?php echo file_get_contents("style/left.txt");?>]">						
						<?php }?>
					</form>                                               
					</div>
					<br>
					<br>
					<span style="font-size:125%">Осталось <?php echo $cnt;?> слов<?php 
						if($cnt%10==1 && $cnt!=11)echo "о";
						else if($cnt%10>1 && $cnt%10<5 && ($cnt<10 || $cnt>19))echo "а";
					?>.</span>
				<?php }?>
				</div>
	            <?php if($left != -1){?>
				<div id="time"> 
					Осталось<br>
					<span id="timer">
						0.0
					</span><br>
					секунд
				</div>
				<?php }?>
			</div>            
		</div>
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>
