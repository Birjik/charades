<?php
	setcookie("time",time()-3600);
    if(isset($_POST["end"])){
?>    	
	<div class = "message">
	        Вы уверены что хотите закончить игру?<br>
			<form action = "2.php" method = "post">
			 	<input type="submit" name="END" value=«yes»>
				<input type="submit" name="NO" value=«no»>
			</form>
	</div>			
<?php  
    }
    if(isset($_POST["END"])){
	  	$m = new mysqli("localhost","root","","archive_crocodile");			
		$m -> query("SET NAMES 'cp1251'");
    	$m -> query("UPDATE `archive` SET `cur`='results' WHERE `status`='running'");			   	
	    $m -> close();
		header("Location:http://www.crocodile.kz/results.php");
    	exit;
    }
    if(isset($_POST["open"])){
?>
	<div class = "message">
	        Вы уверены что хотите открыть все слова?<br>
			<form action = "2.php" method = "post">
			 	<input type="submit" name="YES" value=«yes»>
				<input type="submit" name="NO" value=«no»>
			<br>
			<font size="2">
			* Предупреждение: посмотрев слова вы не сможете продолжить игру.</font>
			</form>
	</div>			
<?php
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
    if(isset($_POST["YES"])){
		$table = $id."_2"; 
		$result = $m -> query("SELECT * FROM `$table`");
		while(($row = $result->fetch_assoc()) != false){
		    $p = $row['point'];
		    $t = $row['type'];
		    if($row['take'] == "")
				$m -> query("UPDATE `$table` SET `take`='x?' WHERE `point`='$p' AND `type` = '$t'");			   	
		}
	}
	$table = $id."_2"; 
	$result = $m -> query("SELECT * FROM `$table`");
	$cnt1 = 0;
	while(($row = $result->fetch_assoc()) != false){
	    if($row['take'] != "")
	    	++$cnt1;
	}
	$table = $id."_teams";
	$result = $m -> query("SELECT * FROM `$table`");
	$n = $result -> num_rows;
	++$cnt1;
	$cur = $cnt1 % $n;
	if($cur == 0)$cur = $n;
	while(($row = $result->fetch_assoc()) != false){
	    if($row['id'] == $cur){
			$name = $row['name'];
	    	break;
	    }
	}
	if($mur != "2"){
		echo "
		<html>               	                                                            			   	
	<head>                                                                       			   	
		<title> Крокодил</title>                                                  			   	
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
	$table = $id."_2"; 
	$result = $m -> query("SELECT * FROM `$table`");
	$our_table = array();
	$take = array();
	while(($row = $result->fetch_assoc()) != false){
	   $st = $row['point'] / 10;
	   $our_table[$row['type']][$st] = $row['word'];
	   $take[$row['type']][$st] = $row['take'];
	}
?>
<script type="text/javascript">   	
	function deleteCookie(name){
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString(); 
	}
	deleteCookie("time");
</script>
<html>
	<head>
		<title>Тур 2</title>
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
				<span id="str">Тур 2 (Игра  #<?php echo $id;?>)</span>
				<hr>
				<div id="center">
					<?php if($cnt1 == 17 || $cnt1 == 21){
					?>
					<span style="color:grey; font-size:200%">&nbsp;</span>
					<?php } else {?>
					<span style="color:grey; font-size:200%">Выбирает команда : <span style="color:green"><?php echo $name;?></span> (# <?php echo $cur;?>)</span>
					<?php  }?>
				</div>
		        <div id="sectur_help">
	            <table id="sectur" cellspacing="0">
	            <tr id="fst"><td id="categ">&nbsp;</td><td>&nbsp;</td><td id="green">10</td><td id="yellow">20</td><td id="orange">30</td><td id="red">40</td></tr>
				<?php
				    $opened = 1;
					for($i=1;$i<=4;++$i){
						echo "<tr><form action='word.php#add_word' method='post'>";
						for($j=0;$j<=4;++$j){
						    $wd = $our_table[$i][$j]; 
						    if($j==0){
						    	echo "<td id='categ'>$wd&nbsp;</td><td>&nbsp;</td>";
						    }
						    else{
						    if($take[$i][$j] == ''){
						    	echo "<td id='oth'><input type='submit' value='?' name='$i$j'></td>";
						    	$opened = 0;
						    }
						    else if($take[$i][$j] == "x?")echo "<td id='open'>$wd</td>";
						    else if($take[$i][$j][0] == 'x')echo "<td id='lose'>$wd</td>";
						    else echo "<td id='win'>$wd</td>";
							}
						}
					    echo "</form></tr>";
					}
				?>
				 </table>
				 </div>
				 <br>
				<div id="center">
				 <div id="Save">
				    <form action="2.php" method="post">
						<?php
							if($opened == 1){ ?>								
							<span id="zer">Все слова открыты</span>
					   <?php }
							else{?>
							<input name="open" type="submit" value=«Открыть все слова">
						 	<?php 
						 	}
						 	?>
						<input name="end" type="submit" value=«Закончить тур">
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
