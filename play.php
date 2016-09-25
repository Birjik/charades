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
	$AT2 = -1;
	$DT2 = -1;
	$cur = -1;
	$id = -1;
	while(($row = $result->fetch_assoc()) != false){
	    if($row['status'] == 'running'){
	    	$AT2 = $row['author'];
			$DT2 = $row['datep'];
			$cur = $row['cur'];
			$id = $row['id'];
	    }
		if($row['status'] == 'not'){
			++$len;		                                                                 
			$ID[$len] = $row['id'];
			$AT[$len] = $row['author'];
	    	$DT[$len] = $row['datep'];
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
		if($_GET['mistake']){
?>
	    <div class = "message">
		    <form action='play.php' method='post'>
		        �� �� ������� ������ ������� ����, �� �������� ���� �<?php echo $id;?><br>
				<input type='submit' value='��'> 
			</form>
		</div>	
<?php 	}    	
    if(isset($_GET['start'])){
    	$start = $_GET['start'];
    	if($_POST['ans']){
			if($_POST['ans'] == '���'){
				header("Location:http://www.crocodile.kz/play.php");
				exit;			
			}
			else{
				$m -> query("UPDATE `archive` SET `cur`='registration' WHERE `id`='$start'");			   			
				$m -> query("UPDATE `archive` SET `status`='running' WHERE `id`='$start'");			   			
				header("Location:http://www.crocodile.kz/registration.php");
				exit;			
			}
    	}
    	else if(isset($id) && $id != $_GET['start']){
    ?>
	    <div class = "message">
		    <form action='play.php?start=<?php echo $start;?>' method='post'>
		        �� ������� ��� ������ ������ ����?<br>
				<input type='submit' name='ans' value='��'> 
				<input type='submit' name='ans' value='���'> 
				<br>				
				<font size="2">
				* ��������������: �� �������� ������� ����, ���������� ������ ���������.</font>
				<br>
			</form>
		</div>	
    <?php 
       } 
    }
    function convert($s){
		$months = array();
		$months[1] = "������";
		$months[2] = "�������";
		$months[3] = "�����";
		$months[4] = "������";
		$months[5] = "���";
		$months[6] = "����";
		$months[7] = "����";
		$months[8] = "�������";
		$months[9] = "��������";
		$months[10] = "�������";
		$months[11] = "������";
		$months[12] = "�������";
    	$sec = "";
    	$result = "� ";
    	$ok = false;
    	for($j = 0;$j < strlen($s);++ $j){
    		if($s[$j] == ' '){
    			$ok=true;
    			continue;
    		}
    		if($ok == true)$result.=$s[$j];
    	}
    	$ok = 0;
    	$qwe = "";
    	for($j = 0;$j < strlen($result);++ $j){
    		if($result[$j] == ':'){++$ok;if($ok == 2)break;}
			$qwe .= $result[$j];    		
    	}
    	$result = $qwe;
    	$ok = 0;
    	$qwe = "";
    	for($j = 0;$j < strlen($s);++ $j){
    		if($s[$j]=='-'){
    			++$ok;
    			continue;
    		}
    		if($s[$j] == ' ')break;
    		if($ok == 2)$qwe .= $s[$j];
    	}
    	if($qwe[0] != '0')$sec .= $qwe[0];
    	$sec.=$qwe[1];
    	$ok = 0;
    	$qwe = "";
    	for($j = 0;$j < strlen($s);++ $j){
    		if($s[$j]=='-'){
    			++$ok;
    			continue;
    		}
    		if($s[$j] == ' ')break;
    		if($ok == 1)$qwe .= $s[$j];
    	}
    	$sec.=' '.$months[$qwe + 0].' ';
    	for($j = 0;$j < strlen($s);++ $j){
    		if($s[$j]=='-')break;
    		$sec .= $s[$j];
    	}
    	$sec .= ' '.$result;
    	return $sec;
    }
?> 
<html>
	<head>  
		<title>����</title>
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
<?php
	if($AT2 != -1){      
	    $DT2 = convert($DT2);
	    echo "
		<div id='str'>� �������� :</div>
		<table cellspacing='0' align='center' id='result'>
		<tr id='fst'><td>�</td><td>&nbsp;�����</td><td>���� �������������</td><td>&nbsp;</td></tr>
		<tr id='other'><td>$id</td><td>$AT2</td><td>$DT2</td><td><a href='$cur.php' style='color:orange'>������</a></td></tr>
		</table>                                                                                                                               
		<br>";
		if($len > 0)echo "<div id='str'>��� ����:</div>";
	}
	else if($len > 0){
		echo "<div id='str'>���� ������� �� ������ ������ :</div>";	
	}
	if($len > 0){
?>
<table cellspacing="0" align="center" id="result">
<tr id="fst"><td>�</td><td>&nbsp;�����</td><td>���� �������������</td><td>&nbsp;</td></tr>
<?php
	for($i = 1;$i <= $len;$i ++){
		$index = $ID[$i];
	    $go = "start=".$index;
	    if($AT2 != -1)$go = "mistake=1";
		$new = convert($DT[$i]);
		echo "<tr id='other'>
		<td>".$index."</td><td>".$AT[$i]."</td><td>".$new."</td>
		<td><a href='play.php?$go' style='color:green'>������</a></td></tr>";
	}                                                           
?>
</table>
			</div>
<?php 
}
if($len == 0 && $AT2==-1){
?>
<div id="str">� ��������� ���� ��� ���</div>
<?php }
?>		      
		</div>    
	</div>	
		<div id="footer">
			&copy; ������� ������ 2014
		</div> 
	</body>
</html>
