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
				<a href="play.php">
					<div id="button">
						����
					</div>
				</a>
				<a href="archive.php">
					<div id="button">
						�����		
					</div>
				</a>
				<a href="add.php">
					<div id="button">
						�������� ����			
					</div>
				</a>
				<a href="about.html">
					<div id="button">
						�� ����			
					</div>
				</a>
			</div>         
		</div>    
		<div id="footer">
			&copy; ������� ������ 2014
		</div> 
	</div>	
	</body>
</html>
