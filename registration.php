<?php
	setcookie("timer",time()-3600);
?>
<html>               	                                                            			   	
	<head>                                                                       			   	
		<title>Регистрация команд</title>                                                  			   	
		<link rel="stylesheet" type="text/css" href="../style/style.css">                 			   	
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>                                                                      			   	
<?php
	file_put_contents("style/need.txt",'x');
	function swap(&$x,&$y){
		$z=$y;$y=$x;$x=$z;
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `archive`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['status'] == 'running'){
	    	$id = $row['id'];
	     	$cur = $row['cur'];
	     	$author = $row['author'];
	    	break;
	    }
	}
	if($cur != "registration"){
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
	$table = $id."_teams";
	$result = $m -> query("SELECT * FROM `$table`");
	$n = $result -> num_rows;
	if($_GET['end']){
?>
		<div class = "message">
			 	Вы уверены что имена всех команд и участников написаны правильно?
			 	<br>
				<form action = "lib/upd.php?end=<?php echo $id;?>" method = "post">
				 	<input type="submit" name="ok" value="Да">
					<input type="submit" name="ok" value="Нет">
				</form>
		</div>			
<?php 
}
?>
<script type="text/javascript">
	function deleteCookie(name){
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString(); 
	}
	deleteCookie("timer");
</script>
	<body>                                                                       			   	
		<div id="all">                                                           			   	
			<div id="header">                                                    			    
			    <h1>                                                             			    
    				<a href="http://www.crocodile.kz">                               			    
					    Крокодил                                                 			    
    				</a>                                                         			    
			    </h1>                                                            			    
			</div>                                                               			    
<?php
	$table = $id."_teams";
	$m -> query("
    CREATE TABLE IF NOT EXISTS `$table`(
      `id` int(11) unsigned NOT NULL,
      `name` varchar(255) NOT NULL,
      UNIQUE KEY `id` (`id`,`name`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");  
	$result = $m -> query("SELECT * FROM `$table`");
	$ID = array();
	$NAME = array();
	$len = 0;
	while(($row = $result->fetch_assoc()) != false){
	    $NAME[++$len] = $row['name'];
	    $ID[$len] = $row['id'];
    }
	for($i=1;$i<=$len;$i++){
		for($j=$i+1;$j<=$len;$j++){
			if($ID[$i]>$ID[$j]){
				swap($ID[$i],$ID[$j]);
				swap($NAME[$i],$NAME[$j]);
			}
		}
	}	
?>
			<div id="content">
				<div id="finish">
				<span id="str">
				Автор: <?php echo $author;?>
				</span>
	        	</div>
				<span id="str">Регистрация команд (Игра №<?php echo $id;?>)</span>
				<hr>
				    <ul id="registr">
					<?php
						for($i = 1;$i <= $len;++$i){
						    $nn = $NAME[$i];
						    $ii = $ID[$i];
						    $wr = "word".$ii;
						    if($i!=1)echo "<br>";
							echo "<li><span id='need$ii'style='font-size:125%'>Команда №$ii</span><br>
							<form action = 'lib/upd.php?add=$id' method = 'post'>
							Название команды:
							<input type = 'text' value='$nn' name='$wr' id='invise'>&nbsp;
							<input id='show_save' type = 'submit' value=' ' name='$ii'>
							<input id='show_del' type = 'submit' value	='.' name='$ii'>
							</form>";
						  	$m = new mysqli("localhost","root","","archive_crocodile");			
							$m -> query("SET NAMES 'cp1251'");
							$table = $id."_teams_".$ii;
							$result = $m -> query("SELECT * FROM `$table`");
							$NM2 = array();
							$ID2 = array();
							$len2 = 0;
							while(($row = $result->fetch_assoc()) != false){
							    $NM2[++$len2] = $row['player'];
							    $ID2[$len2] = $row['id'];
						    }
						    echo "<br><ol id='li_circle'>";
						    for($j = 1;$j <= $len2;++ $j){                                  
						        for($j1= $j+1;$j1<=$len2;++$j1){ 
									if($ID2[$j]>$ID2[$j1]){
										swap($ID2[$j],$ID2[$j1]);
										swap($NM2[$j],$NM2[$j1]);
									}
						    	}
						    }
						    for($j = 1;$j <= $len2;++ $j){
						    	$index = $ID2[$j];

						    	echo "
						    	<span id = '$index $ii'>
						    	<li> 
								<form action = 'lib/upd.php?change_player=$id' method = 'post'>
						    		Участник ".$index." <input type='text' value='".$NM2[$j]."' name='must'>
	    							<input id='show_save' type = 'submit' value = ' ' name='$ii $index'>
									<input id='show_del' type = 'submit' value = '.' name='$ii $index'>
						    	</form>
						    	</li>
						    	";
						    }
						    echo "</ol>";
						    $m -> close();
							echo "
								<form action = 'lib/upd.php?add_player=$id' method = 'post'>
									<input type='submit' name = '$ii' value=' ' id='show_add'> 
									<span style='font-family:Verdana; color:#777; font-size:72.5%'>Добавить участника </span>
								</form>						
								";
							echo "</li>
					    	</span>
							";	
					    }
					                                                                                 
					?>
					</ul>
					<form action = "lib/upd.php?add=<?php echo $id;?>" method = "post">
						<div id="save">
							<?php if($n<=7){?>
								<span id = "need"><input type="submit" value="Добавить команду" name="add"></span>
							<?php } else {?>
								<span id ="need"><span id = "zer">Добавить команду</span></span><?php }?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php if($n==0){								
							echo "<span id='zer'>Готово</span>";}else {?><a href='/registration.php?end=1'><input type="button" value="Готово"></a>
							<?php } ?>
						</div>	
					</form>
			</div>         
		</div>    
		<div id="footer">                                                         
			&copy; Ауганов Биржан 2014
		</div> 
	</div>	
	</body>
</html>
                                                                     