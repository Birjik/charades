<?php
	setcookie("timer",time()-3600);
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
	if($mur != "res1"){
		echo "
		<html>               	                                                            			   	
	<head>                                                                       			   	
		<title>��������</title>                                                  			   	
		<link rel='shortcut icon' href='images/favicon.ico'>
		<link rel='stylesheet' type='text/css' href='../style/style.css'>                 			   	
	</head>                                                                      			   	
	<body>                                                                       			   	
		<div id='all'>                                                           			   	
			<div id='header'>                                                    			    
			    <h1>                                                             			    
    				<a href='http://www.crocodile.kz'>                               			    
					    ��������                                                 			    
    				</a>                                                         			    
			    </h1>                                                            			    
			</div>                                                               			    
			<div id='content'>                                                   			    
				<h1 align='center' style='color:red'>��������� ������!</h1>
			</div>
		</div>
		<div id='footer'>
			&copy; ������� ������ 2014
		</div>
		</body>
	    </html>";	
		exit;	
	}
	file_put_contents("style/need.txt",'x');
	if($_POST["next"]){
		$m -> query("UPDATE `archive` SET `cur`='2' WHERE `status`='running'");			   	
		$m -> close();
		header("Location:http://www.crocodile.kz/2.php");
		exit;
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
	for($i = 1;$i <= $n;++ $i)$KOL[$i] = 0;
	$table = $id."_1";
	$result = $m -> query("SELECT * FROM `$table`");
	while(($row = $result->fetch_assoc()) != false){
	    if($row['take'] != "{[]}")
	    	$KOL[$row['take']] += $row['point'];
	}
	for($i = 1;$i < $n;$i ++)
		for($j = $i + 1;$j <= $n;$j ++){
			if($KOL[$i] < $KOL[$j]){
				swap($KOL[$i],$KOL[$j]);
				swap($TEAMS[$i],$TEAMS[$j]);
			}
		}
	$m -> close();
?>
<script type="text/javascript">
	function deleteCookie(name){
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString(); 
	}
	deleteCookie("timer");
</script>
<html>
	<head>
		<title>��������</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>
	<body>
		<div id="all">
			<div id="header">
			    <h1>
    				<a href="http://www.crocodile.kz">                               			    
				    	��������
    				</a>
			    </h1>
			</div>
			<div id="content">
			    <div id="finish">
					<span id="str">
						�����: <?php echo $author;?>
					</span> 
				</div>
				<span id="str">                                                 
					���������� ����� ������� ���� (���� �<?php echo $id;?>)
				</span> 
				<hr>
<table id="result" align="center">
<tr id="fst"><td>�����</td><td>�������</td><td>����</td></tr>
<?php
for($i = 1;$i <= $n;++ $i)echo "<tr id='other'><td align='center'>".$i."</td><td id='name_of_team'>".$TEAMS[$i]."</td><td align='center'>".$KOL[$i]."</td></tr>";
?>
</table>
        <div id="center">
		    <br>
			<div id="add_word">
				<form action="res1.php" method="post">
					<input type="submit" name= "next" value="������">
				</form>
			</div>
		</div>
			</div>
		</div>
		<div id="footer">
			&copy; ������� ������ 2014
		</div>
	</body>
</html>
