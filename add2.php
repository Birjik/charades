<?php
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `settings`");
	while(($row = $result->fetch_assoc()) != false){
		$a = $row;
		$i++;
	}
	$next = $a['next'];
	$prepare = $a['prepare'];
	if($a['prepare']==0){
	    $prepare=$next;                
		$m->query("UPDATE `archive_crocodile`.`settings` SET  `prepare` =  '$next'");
		$next++;		 
		$m->query("UPDATE `archive_crocodile`.`settings` SET  `next` =  '$next'");		 
	}
	$kol=0;
    $tab=$prepare."_2";
	$m->query("
	CREATE TABLE IF NOT EXISTS `$tab`(
	  `word` varchar(50) NOT NULL,
	  `point` int(11) unsigned NOT NULL,
	  `type` varchar(50) NOT NULL,
	  `take` varchar(50) NOT NULL
	) 
	ENGINE=MyISAM DEFAULT CHARSET=utf8;
  	");
	$result = $m -> query("SELECT * FROM `$tab`");
	$i = 0;
	$table = array();
	for($i = 1;$i <= 4;++ $i)$table[i] = array();
	while(($row = $result->fetch_assoc()) != false){
		$i++;
		$table[$row['type']][$row['point']/10] = $row['word'];
	}
	$name = $pr = -1;
	if(isset($_POST['author'])){
		$nm = $_POST['author'];
		if($nm != "»ван »ванов"){
			$next = 0;
			$tb = $prepare."_1";
			$cc = $m -> query("SELECT * FROM `$tb`");
			$cc = $cc -> num_rows;
			if($cc==0){
				header("Location:http://www.crocodile.kz");
	        	exit;
			}
			else{
				$m->query("UPDATE `archive_crocodile`.`settings` SET  `prepare` = '$next'");
				$cc = $m -> query("SELECT * FROM `archive`");
				$kol = $cc -> num_rows;
				++$kol;
  	 	 	  	$m->query("INSERT INTO `archive` (`id`,`author`,`status`) VALUES ('$kol','$nm','not')");
  	 	 	  	$m->close();
				header("Location:http://www.crocodile.kz/play.php");
				exit;
			}
		}
	}
?>
<html>
	<head>                                                
		<title>Добавление игры</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">                 			   	
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>
<?
	if(isset($_GET["problem"]))$pr = $_GET["problem"];
	if($prepare != 0){
	if($pr == 1){?>
		<div class = "message">
		    <form action='add2.php' method='post'>
			    В первом туре требуется минимум 30 слов!<br>
				<input type="submit" name="ok" value="OK">
			</form>
		</div>
	<?	
	}
	if($pr == 2){		
	?>	
		<div class = "message">
		    <form action='add2.php' method='post'>
			    Проверьте орфографию и наличие пустых клеток второго раунда!<br>
				<input type="submit" name="ok" value="OK">
			</form>
		</div>	
	<?
	}
	if($pr == 0){?>
		<div class = "message">
		    <form action='add2.php' method='post'>
			    Благодарим за подготовку тура!<br>
<input type="text" name="author" style="color: #777;" value=«Иван иванов" 
onfocus="if (this.value == '»ван »ванов') {this.value = ''; this.style.color = '#000';}" 
onblur="if (this.value == '') {this.value = '»ван »ванов'; this.style.color = '#777';}" /><br>
				<font size="2">* это имя будет отображаться как автор тура.</font><br>
				<input type="submit" name="ok" value="OK">
			</form>
		</div>	
	<?}
	}
?>
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
				<div id="center"><span id="str">Подготовление игры є<?php echo $prepare;?></span></div>
		       	<form action="lib/upd.php?game=<?php echo $prepare;?>" method="post">
		       	   <table border = "0">
		       	   		<tr id="first"> <td>&nbsp;</td><td id="cat"> атегори€</td> <td id="green">10</td> <td id="yellow">20</td> <td id="orange">30</td> <td id="red">40</td></tr>
		       	   		<tr> <td id = "num">1.</td> <td><input class = "word2" name="10" value="<?php echo $table[1][0];?>"></td> <td><textarea class="square" name="11"><?php echo $table[1][1];?></textarea></td> <td><textarea class="square" name="12"><?php echo $table[1][2];?></textarea></td> <td><textarea class="square" name="13"><?php echo $table[1][3];?></textarea></td> <td><textarea class = "square" name="14"><?php echo $table[1][4];?></textarea></td></tr>
		       	   		<tr> <td id = "num">2.</td> <td><input class = "word2" name="20" value="<?php echo $table[2][0];?>"></td> <td><textarea class="square" name="21"><?php echo $table[2][1];?></textarea></td> <td><textarea class="square" name="22"><?php echo $table[2][2];?></textarea></td> <td><textarea class="square" name="23"><?php echo $table[2][3];?></textarea></td> <td><textarea class = "square" name="24"><?php echo $table[2][4];?></textarea></td></tr>
		       	   		<tr> <td id = "num">3.</td> <td><input class = "word2" name="30" value="<?php echo $table[3][0];?>"></td> <td><textarea class="square" name="31"><?php echo $table[3][1];?></textarea></td> <td><textarea class="square" name="32"><?php echo $table[3][2];?></textarea></td> <td><textarea class="square" name="33"><?php echo $table[3][3];?></textarea></td> <td><textarea class = "square" name="34"><?php echo $table[3][4];?></textarea></td></tr>
		       	   		<tr> <td id = "num">4.</td> <td><input class = "word2" name="40" value="<?php echo $table[4][0];?>"></td> <td><textarea class="square" name="41"><?php echo $table[4][1];?></textarea></td> <td><textarea class="square" name="42"><?php echo $table[4][2];?></textarea></td> <td><textarea class="square" name="43"><?php echo $table[4][3];?></textarea></td> <td><textarea class = "square" name="44"><?php echo $table[4][4];?></textarea></td></tr>
					</table>
					<br>
					<div id="margl">
						<div id="Save">
							<input type="submit" value=«Сохранить">
						</div>
					</div>
				</form>	
			</div>            
			<div id="st">
				<a href="http://www.crocodile.kz/add.php">
					Тур 1
				</a>   
        	</div>
			<div id="finish">
				<a href="http://www.crocodile.kz/lib/finish.php?id=<?php echo $prepare;?>">
					Готово
				</a>   
        	</div>
		</div>                                     
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>
