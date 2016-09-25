<?php
    if($_GET["change"]){
	  	$m = new mysqli("localhost","root","","archive_crocodile");			
		$m -> query("SET NAMES 'cp1251'");
 	 	$result = $m -> query("SELECT * FROM `archive`");
		while(($row = $result->fetch_assoc()) != false){
		    if($row['status'] == 'running'){
		        $id = $row['id'];
		    	$table = $id."_teams";
		    	break;
		    }
		}
		$result = $m -> query("SELECT * FROM `$table`");
		$n = $result -> num_rows;
		$num = file_get_contents("style/need.txt") + 1;
		if($num == $n+1)$num = 1;
        file_put_contents("style/need.txt",$num);				        
		header("Location:http://www.crocodile.kz/2.php");				
    	exit;
    }
	setcookie("timer",time()-3600);
?>
<html>
	<head>
		<title>Hello World!</title>
		<script type="text/javascript">   	
		    function setCookie(name, value){
		    	document.cookie = name + "=" + value;
		    }
			function deleteCookie(name){
				var date = new Date();
				date.setTime(date.getTime() - 1);
				document.cookie = name += "=; expires=" + date.toGMTString(); 
			}
			function getCookie(name){
				var r = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
				if(r) return r[2];
				else return "";
			}
			deleteCookie("timer");
			document.location.href = "http://www.crocodile.kz/1.php";
			document.write(docment.location.href);
		</script>
	</head>
	<body>
	</body>
</html>
