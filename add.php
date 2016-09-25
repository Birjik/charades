<?php
    function okw($str){
    	for($l = 0;$l < strlen($str);++ $l){
    		if('0' <= $str[$l] && $str[$l] <= '9')return true;
    		if('А' <= $str[$l] && $str[$l] <= 'Я')return true;
    		if('а' <= $str[$l] && $str[$l] <= 'я')return true;
    		if('A' <= $str[$l] && $str[$l] <= 'Z')return true;
    		if('a' <= $str[$l] && $str[$l] <= 'z')return true;
    		if($str[$l] == 'і')return true;
    	}
    	return false;
    }
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `settings`");
	while(($row = $result->fetch_assoc()) != false){
		$a = $row;
		$i++;
	}
	$next = $a['next'];
	$prepare = $a['prepare'];
	$word = $_POST["word"];      
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	if($prepare==0){
	    $prepare=$next;
	    $table=$prepare."_1";
		$m->query("UPDATE `archive_crocodile`.`settings` SET  `prepare` =  '$next'");
		$next++;		 
		$m->query("UPDATE `archive_crocodile`.`settings` SET  `next` =  '$next'");		 
		$next = $a['next'];
		$prepare = $a['prepare'];
		$m->query("
		CREATE TABLE IF NOT EXISTS `$table`(
		  `id` int(11) unsigned NOT NULL,
		  `word` varchar(50) NOT NULL,
		  `point` int(11) unsigned NOT NULL,
		  `take` varchar(50) NOT NULL
		) 
		ENGINE=MyISAM DEFAULT CHARSET=utf8;
   	 	");
	}
    else{
        $table=$a['next']-1;
        $prepare=$table;
        $table.="_1";
		$m->query("
		CREATE TABLE IF NOT EXISTS `$table`(
		  `id` int(11) unsigned NOT NULL,
		  `word` varchar(50) NOT NULL,
		  `point` int(11) unsigned NOT NULL,
		  `take` varchar(50) NOT NULL
		) 
		ENGINE=MyISAM DEFAULT CHARSET=utf8;
   	 	");
  	}
	$kol=0;
	$ok=true;
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
		if($row['word']==$word)$ok=false;
        $kol++;
    }
    $n = $result->num_rows;
    ++$n;
    if($ok && $word!="" && okw($word)){
		$m->query("INSERT INTO `$table` (`id`,`word`,`point`,`take`) VALUES ('$n','$word',10,'')");
		$kol++;
	}
	$m -> close();
?>
<html>
	<head>
		<title>Добавление игры</title>
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
				<div id="center"><span id="str">Подготовление игры №<?php if($prepare==0)echo $next;else echo $prepare;?></span></div>
	        	<h1 align="center"><i>Введите слово, которое хотели добавить</i></h1>
	        	<form action="add.php" method="post">
					<div id="word">
						<input type="text" name="word">
					</div>
					<div id="add_word">
						<input type="submit" value="Добавить">
					</div>
				</form>
				<div id="count">
					В словаре имеются  
					<?php 
						echo "<a href='http://www.crocodile.kz/lib/show.php'>".$kol."</a> слов";
						if($kol%10==1 && ($kol%100)!=11)echo "о";
						else if($kol%10>1 && $kol%10<5 && ($kol<10 || $kol>19))echo "а";
					?>.
				</div>
			</div>            
			<div id="finish">    
				<a href="http://www.crocodile.kz/add2.php">
					Тур 2
				</a>   
        	</div>
		</div>                                     
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>