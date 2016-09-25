<?php   	
//	date_default_timezone_set("Asia/Almaty");
//	echo date('d.m.Y h:i:s',$tm);
?>
<script type="text/javascript">   	
	function deleteCookie(name){
		var date = new Date();
		date.setTime(date.getTime() - 1);
		document.cookie = name += "=; expires=" + date.toGMTString(); 
	}
	function setCookie(name, value){
		document.cookie = name + "=" + value;
	}
	function getCookie(name){
		var r = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
		if(r) return r[2];
		else return "";
	}
	var dt = new Date();
	if(getCookie("timer") == "")setCookie("timer",dt.getTime()-100000000);
	else setCookie("timer",getCookie("timer")-100000000);
</script>
<html>
	<head>  
		<title>Крокодил</title>
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
				<a href="play.php">
					<div id="button">
						Игра
					</div>
				</a>
				<a href="archive.php">
					<div id="button">
						Архив		
					</div>
				</a>
				<a href="add.php">
					<div id="button">
						Добавить игру			
					</div>
				</a>
				<a href="about.html">
					<div id="button">
						Об игре			
					</div>
				</a>
			</div>         
		</div>    
		<div id="footer">
			&copy; Ауганов Биржан 2014
		</div> 
	</div>	
	</body>
</html>
