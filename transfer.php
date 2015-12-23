<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Tugas Akhir Sisdis</title>
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='http://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,800' rel='stylesheet' type='text/css'>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<script src="js/jquery-1.9.1.min.js"></script>
<!--hover-effect-->
<script src="js/hover_pack.js"></script>
<script type="text/javascript" src="js/jquery.mixitup.min.js"></script>
</head>
<body>
	<!--start header-->
	<div class="header">
	  <div class="header-top">
		<div class="container">
			<div class="menu">
			  <a class="toggleMenu" href="#"><img src="images/nav_icon.png" alt="" /></a>
				<ul class="nav" id="nav">
				   <li><a href="index.php" class="active">BERANDA</a></li>
					<li><a href="ping.php" class="active">PING</a></li>
					<li><a href="register.php" class="active">REGISTER</a></li>
					<li><a href="getSaldo.php" class="active">GET SALDO</a></li>
					<li><a href="transfer.php" class="btn btn-warning">TRANSFER</a></li>
					<li><a href="getTotalSaldo.php" class="">GET TOTAL SALDO</a></li>
				   <div class="clear"></div>
			    </ul>
			</div>							
	        <div class="clear"></div>
	        <script type="text/javascript" src="js/responsive-nav.js"></script>
			<script type="text/javascript" src="js/move-top.js"></script>
			<script type="text/javascript" src="js/easing.js"></script>
		   </div>
		 </div>
		 <div class="header-bottom" id="home">
			<div class="container" style="background: rgba(82, 220, 242, .7);">
				<form action="transfer.php" method="post" role="form"style="margin-bottom:20px;">
					<div class="form-group">
					  <p style="font-family: 'caviar_dreamsregular';color: #FFF;font-size: 2.7em;padding: 10px;width: 48%;margin: 0px auto 2%;" ><label for="usr">URL WSDL</label></p>
					  <input type="text" class="form-control" name ="url" id="usr">
					</div>
					<div class="form-group">
					  <p style="font-family: 'caviar_dreamsregular';color: #FFF;font-size: 2.7em;padding: 10px;width: 48%;margin: 0px auto 2%;" ><label for="usr1">USER ID</label></p>
					  <input type="text" class="form-control" name ="npm" id="usr1">
					</div>
					<div class="form-group">
					  <p style="font-family: 'caviar_dreamsregular';color: #FFF;font-size: 2.7em;padding: 10px;width: 48%;margin: 0px auto 2%;" ><label for="usr2">NILAI</label></p>
					  <input type="text" class="form-control" name ="nilai" id="usr2">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
				<?php
					if($_SERVER['REQUEST_METHOD'] == 'POST')
					{
						$wsdl = $_POST["url"];
						$npm = $_POST["npm"];
						$nilai = $_POST["nilai"];
						ini_set("soap.wsdl_cache_enabled", "0");
						$client = new SoapClient($wsdl, array('trace'=>1));
						
						$servername = "localhost";
						$username = "root";
						$password = "Sisdis";
						$dbname = "mydb";
						$saldo =0;
						// Create connection
						$conn = new mysqli($servername, $username, $password, $dbname);
						// Check connection
						if ($conn->connect_error)
						{
							die("Connection failed: " . $conn->connect_error);
						}
						$sql = "SELECT S.saldo FROM Register R, Saldo S where R.user_id=S.user_id and S.user_id='1106020056' ";
						$result = $conn->query($sql);
						if ($result->num_rows > 0)
						{
							while($row = $result->fetch_assoc())
							{
								$saldo =  $row["saldo"];
							}
							
						}
						if($saldo < $nilai)
						{
							echo "<h2>SALDO ANDA TIDAK MENCUKUPI</h2>";
						}
						else
						{	
							$return = $client->transfer($npm, $nilai);
							$sql = "SELECT S.saldo FROM Register R, Saldo S where R.user_id=S.user_id and S.user_id='1106020056' ";
							$result = $conn->query($sql);
							if ($result->num_rows > 0)
							{
								while($row = $result->fetch_assoc())
								{
									$saldo =  $row["saldo"];
								}
								
							}
							//echo $return;
							if($return == -1)
							{
								echo "<h2>USER BELUM TERDAFTAR</h2>";
							}
							else
							{
								//echo $saldo;
								$newSaldo = $saldo - $nilai;
								$sql = "UPDATE Saldo SET saldo=$newSaldo  WHERE user_id='1106020056' ";
								if (mysqli_query($conn, $sql))
								{
									echo "<h2>TRANSAKSI BERHASIL</h2>";
								}
								else
								{
									echo "Error updating record: " . mysqli_error($conn);
								}
							}
						}
					}
				?>
			</div>
		 </div>
	</div>
	<!--end header-->
	  <div class="footer-bottom">
	  	<div class="container">
	  		<div class="copy">
			    <p>Template by  <a href="http://w3layouts.com" target="_blank"> w3layouts</a></p>
		    </div>
	  	</div>
	  </div>
	  <!--end contact-->

</body>
</html>