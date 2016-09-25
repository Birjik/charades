<?php
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `settings`");
	while(($row = $result->fetch_assoc()) != false){
		$a = $row;
		$i++;
	}
    $id = $a['prepare'];                                                           	                               			
?>
<html>               	                                                            			   	
	<head>                                                                       			   	
		<title>Добавление игры</title>                                                  			   	
		<link rel="stylesheet" type="text/css" href="../style/style.css">                 			   	
		<link rel='shortcut icon' href='../images/favicon.ico'>
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
<?php
	if($id == 0){
		echo "
			<div id='content'>                                                   			    
			<h1 align='center' style='color:red'>Произошла ошибка!</h1>
			</div>
		</div>
		<div id='footer'>
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>
		";	
		exit;
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");                                           	                               			
	$table = $id."_1";
	function swap(&$x,&$y){
		$z=$y;$y=$x;$x=$z;
	}
	$result = $m -> query("SELECT * FROM `$table`");                             				
	$n = $result -> num_rows;
	$ID = array();
	$WORD = array();
	$POINT = array();
	$i = 0;
	while(($row = $result->fetch_assoc()) != false){
	    $i++;
		$ID[$i]=$row['id'];
		$WORD[$i]=$row['word'];
		$POINT[$i]=$row['point'];
	}
	for($i=1;$i<=$n;$i++){
		for($j=$i+1;$j<=$n;$j++){
			if($ID[$i]>$ID[$j]){
				swap($ID[$i],$ID[$j]);
				swap($WORD[$i],$WORD[$j]);
				swap($POINT[$i],$POINT[$j]);
			}
		}
	}
	for($i=1;$i<=$n;$i++){
		$x = $ID[$i];
		$y = $WORD[$i];
 	  	$m -> query("UPDATE `$table` SET `id`='$x' WHERE `word`='$y'");			   	
	}
?>
			<div id="content">                                                   			    
				<div id = "center">
				<span id="str">Подготовление игры №<?php echo $id;?></span>
				<br><br>                                     			    
<?php                                                                            			    
	if(isset($id)){                                                              			    
		echo "<table align='center' id='show_words'>";
		$kol = 0;
		$result = $m -> query("SELECT * FROM `$table`");
		$n = $result -> num_rows;                            				
		$mx = ceil($n / 13);
		if($result!=""){
			$page  = $_GET["page"];
			if(!isset($_GET["page"]))$page  = 1;
			$l = ($page - 1) * 13 + 1;
			$r = $page * 13;
			$qwe = 13;
			while($kol < $n){
			    $kol++;
			    if($l <= $kol && $kol <= $r){
			    $qwe--;
				echo "<form action='upd.php?id=$id&page=$page' method='post'>\n";
				echo "<tr><td>".$ID[$kol].".</td><td>&nbsp;</td><td><input type='text' value='$WORD[$kol]' 
				name='$ID[$kol]'></td><td><input id='show_save' type='submit' value=' '></td>
				<td><input id='show_del' type='submit' value='.' name='$ID[$kol]'></td></tr>";                                  
				echo "</form>";		
		    	}
			}
        }
        for($i = 1;$i <= $qwe;++ $i)echo "<tr><td>&nbsp;</td></tr>";
        $left = $page - 1;
        $right = $page + 1;
        if($right>$mx)$right=1;
        if($left<1)$left=$mx;
        echo "</table>\n";
        echo "<font size='4'>";
        echo "<a href='http://www.crocodile.kz/lib/show.php?page=$left'> &#8592; </a>";
    	for($i = 1;$i <= $mx;++ $i){
    		if($i == $page)echo "<span style='color:#00f'>&nbsp;$i&nbsp</span>";
    		else echo "<a href='http://www.crocodile.kz/lib/show.php?page=$i' style='color:grey'>&nbsp;$i&nbsp;</a>";    		
    	}
        echo "<a href='http://www.crocodile.kz/lib/show.php?page=$right'> &#8594; </a>";
    	echo "</font>";
    } 
   	$m -> close();
?>
        	</div>
		</div>
		<div id="finish"><a href="../add.php">Тур 1</a></div>
		</div>
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div>
	</body>
</html>
