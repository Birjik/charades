<?php
	function swap(&$x,&$y){
		$z=$y;
		$y=$x;
		$x=$z;
	}
  	$m = new mysqli("localhost","root","","archive_crocodile");			
	$m -> query("SET NAMES 'cp1251'");
	$result = $m -> query("SELECT * FROM `archive`");
	$len = 0;
	$ID = array();
	$AT = array();
	$DT = array();
	while(($row = $result->fetch_assoc()) != false){
	    if($row['status'] == 'played'){
			++$len;		                                                                 
			$ID[$len] = $row['id'];
			$AT[$len] = $row['author'];
	    	$DT[$len] = $row['date'];
		}
	}                                                                       
	for($i = 1;$i < $len;$i ++)
		for($j = $i + 1;$j <= $len;$j ++){
			if($ID[$i] > $ID[$j]){
				swap($ID[$i],$ID[$j]);
				swap($DT[$i],$DT[$j]);
				swap($AT[$i],$AT[$j]);
			}
		}
	function convert($num){
		if($num == 0)return "no information";
		return "no information";
	}
?>
<html>
	<head>  
		<title>јрхив</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">                 			   	
		<link rel="shortcut icon" href="images/favicon.ico">
	</head>                         
	<body>
		<div id="all">
			<div id="header">
			    <h1>
    				<a href="http://www.crocodile.kz">
				     рокодил
    				</a>
			    </h1>
			</div>
			<div id="content">
				<div id='str'>Архив игр:</div>	
				<table cellspacing="0" align="center" id="result">
					<tr id="fst"><td>є</td><td>&nbsp;јвтор</td><td>Дата</td><td>&nbsp;</td></tr>
<?php
		for($i = 1;$i <= $len;$i ++){
			$index = $ID[$i];
			$date = convert($DT[$i]);
			echo "<tr id='other'>
			<td>".$index."</td><td>".$AT[$i]."</td><td>".$date."</td>
			<td><a href='#' style = 'color:green'>Дальше</a></td></tr>";
		}                                                           
?>
				</table>
			</div>         
		</div>    
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div> 
	</div>	
	</body>
</html>
