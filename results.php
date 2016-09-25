<?php
    file_put_contents("style/need.txt",'x');
	function swap(&$x,&$y){
		$z=$y;
		$y=$x;
		$x=$z;
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `archive`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['status'] == 'running'){
	    	$id = $row['id'];
	    	$author = $row['author'];
	        $mur = $row['cur'];
	    	break;
	    }
	}
	if($mur != "results"){
		echo "
		<html>               	                                                            			   	
	<head>                                                                       			   	
		<title>Крокодил</title>                                                  			   	
		<link rel='shortcut icon' href='images/favicon.ico'>
		<link rel='stylesheet' type='text/css' href='../style/style.css'>                 			   	
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
    if(isset($_POST["END"])){
        $t = time();
     	$m -> query("UPDATE `archive` SET `cur`='' WHERE `status`='running'");			   	
     	$m -> query("UPDATE `archive` SET `date`='$t' WHERE `status`='running'");			   	
     	$m -> query("UPDATE `archive` SET `status`='played' WHERE `status`='running'");			   	
		header("Location:http://www.crocodile.kz");				
		exit;
    }
    if(isset($_POST["next"])){
?>    	
	<div class = "message">
	        Вы уверены что хотите закончить игру?<br>
			<form action = "results.php" method = "post">
			 	<input type="submit" name="END" value="Да">
				<input type="submit" name="NO" value="Нет">
			</form>
	</div>			
<?php 
    }
	$TEAMS = array();
	$ID = array();
	$table = $id."_teams";
	$result = $m -> query("SELECT * FROM `$table`");
	$n = 0;
	while(($row = $result->fetch_assoc()) != false){
		$TEAMS[++$n] = $row['name'];
		$ID[$n] = $row['id'];
	}
	for($i = 1;$i < $n;$i ++)
		for($j = $i + 1;$j <= $n;$j ++){
			if($ID[$i] > $ID[$j]){
				swap($ID[$i],$ID[$j]);
				swap($TEAMS[$i],$TEAMS[$j]);
			}
		}
	$KOL = array();
	$KOL2 = array();
	for($i = 1;$i <= $n;++ $i)$KOL[$i] = $KOL2[$i] = 0;
	$table = $id."_1";
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['take'] != "{[]}")
	    	$KOL[$row['take']] += $row['point'];
	}
	$table = $id."_2";
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['take'][0] != 'x')
	    	$KOL2[$row['take']] += $row['point'];
	}
	for($i = 1;$i < $n;++ $i){
		for($j = $i + 1;$j <= $n;++ $j){	
		    $o1 = $KOL[$i] + $KOL2[$i];
			$o2 = $KOL[$j] + $KOL2[$j];
			if($o1 < $o2){
			    swap($ID[$i],$ID[$j]);
			    swap($KOL[$i],$KOL[$j]);
			    swap($KOL2[$i],$KOL2[$j]);
			    swap($TEAMS[$i],$TEAMS[$j]);
			}
		}
	}
 	$m -> close();
?>
<html>
	<head>
		<title>Результаты</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>
	<body>
		<div id="all">
			<div id="header">
			    <h1>
    				<a href="http://www.crocodile.kz">
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
				<span id="str">Результаты (Игра №<?php echo $id;?>)</span>
				<hr>
<div id="center">
<br>
<?php
	$winners = "";
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	echo "<br><img width='80px' height='80px' src='images/cup.jpg'><span style='font-family:Comic Sans MS; font-size:300%'> Чемпионы </span><img width='80px' height='80px' src='images/cup.jpg'><br><br>";
	$fst = $KOL[1] + $KOL2[1];
	for($t = 1;$t <= $n;++ $t){
		$ob = $KOL[$t] + $KOL2[$t];
	    if($ob < $fst)break;
		$table = $id."_teams_".$ID[$t];
		$result = $m -> query("SELECT * FROM `$table`");
		$champions = $TEAMS[$t];
		$winners .= $ID[$t].".";
		echo "<span style='color:green; font-size:175%'>$champions</span><br>";
		while(($row = $result->fetch_assoc()) != false){
			echo "<span style='font-size:125%'>".$row['player']."</span><br>";
		}
		echo "<br>";
	}
	echo "</div>";
  	$m -> query("UPDATE `archive` SET `winners`='$winners' WHERE `status`='running'");			   	
?>
<br>
<table id="result" align="center">
<tr id="fst"><td>Место</td><td>Команда</td><td>Тур 1</td><td>Тур 2</td><td>Общий</td></tr>
<?php
for($i = 1;$i <= $n;++ $i){
$ob = $KOL[$i] + $KOL2[$i];
echo "<tr id='other'>
<td align='center'>".$i."</td>
<td id='name_of_team'>".$TEAMS[$i]."</td>
<td align='center'>".$KOL[$i]."</td>
<td align='center'>".$KOL2[$i]."</td>
<td align='center'>".$ob."</td>
</tr>";
}
?>
</table>
		    <br>
			<div id="add_word">
				<form action="results.php" method="post">
					<input type="submit" name= "next" value="Выход">
				</form>
			</div>
		</div>
			</div>            
		</div>
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>
