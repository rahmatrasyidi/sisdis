<?php

function quorum()
{
	$cabang = array("https://khanza.sisdis.ui.ac.id/bank/wsdl",
					"https://wiska.sisdis.ui.ac.id/bank/wsdl",
					"https://ghaisani.sisdis.ui.ac.id/bank/wsdl",
					"https://kusumo.sisdis.ui.ac.id/bank/wsdl",
					"https://oenang.sisdis.ui.ac.id/bank/wsdl",
					"https://rahmat.sisdis.ui.ac.id/ta/service.wsdl",
					"https://nabilati.sisdis.ui.ac.id/ta/wsdl",
					"https://ahlunaza.sisdis.ui.ac.id/tugasakhir.wsdl");
	$count=0;
	for($i =0;$i<count($cabang);$i++)
	{
			try {
				#ini_set("soap.wsdl_cache_enabled", "0");
				#$client = new SoapClient($cabang[$i], array('trace'=>1));
				$client = new SoapClient($cabang[$i], array("soap_version" => SOAP_1_2,
							"stream_context"=>stream_context_create(
							array(
								"ssl"=>array(
									"verify_peer"=>true,
									"allow_self_signed"=>false,
									"cafile"=>"ca.crt",
									"verify_depth"=>5)
							)
						)
					));
				$return = $client->__soapCall("ping",array());
				$count++;

			}
			catch(Exception $e) {
			  //echo 'Message: ' .$e->getMessage();
			}
	}
	return $count;
}

function getTotalSaldo($user_id)
{
	if(quorum()==8)
	{
		$cabang = array("https://khanza.sisdis.ui.ac.id/bank/wsdl",
					"https://wiska.sisdis.ui.ac.id/bank/wsdl",
					"https://ghaisani.sisdis.ui.ac.id/bank/wsdl",
					"https://kusumo.sisdis.ui.ac.id/bank/wsdl",
					"https://oenang.sisdis.ui.ac.id/bank/wsdl",
					"https://rahmat.sisdis.ui.ac.id/ta/service.wsdl",
					"https://nabilati.sisdis.ui.ac.id/ta/wsdl",
					"https://ahlunaza.sisdis.ui.ac.id/tugasakhir.wsdl");
		$sum=0;
		for($i =0;$i<count($cabang);$i++)
		{
			ini_set("soap.wsdl_cache_enabled", "0");
			$client = new SoapClient($cabang[$i], array('trace'=>1));
			$return = $client->getSaldo($user_id);
			if($return!=-1)
			{
				$sum=$sum+$return;
			}
		}
		return $sum;
	}
	else
	{
		return;
	}
}

function ping() 
{
	$pingReturn = 1;
	return $pingReturn;
}

function register($user_id, $nama, $ip_domisili)
{
	if(quorum()>=5)
	{
		$servername = "localhost";
		$username = "root";
		$password = "Sisdis";
		$dbname = "mydb";
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error)
		{
				die("Connection failed: " . $conn->connect_error);
		}
		$sql = "INSERT INTO Register (user_id, nama, ip_domisili) VALUES ('$user_id', '$nama', '$ip_domisili')";
		if ($conn->query($sql) === TRUE) 
		{
				echo "Successfully register new user";
		}
		else
		{
				echo "Error: " . $sql . "<br>" . $conn->error;
		}
		$sql = "INSERT INTO Saldo(user_id,saldo) VALUES ('$user_id',0)";
			if ($conn->query($sql) === TRUE)
			{
					echo "Successfully add saldo new user";
			}
			else
			{
					echo "Error: " . $sql . "<br>" . $conn->error;
			}
		$conn->close();
	}
	else
	{
		return;
	}
}

function getSaldo($user_id)
{
	if(quorum()>=5)
	{
		$servername = "localhost";
        $username = "root";
        $password = "Sisdis";
        $dbname = "mydb";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error)
        {
                die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT S.saldo FROM Register R, Saldo S where R.user_id=S.user_id and S.user_id='$user_id' ";
        $result = $conn->query($sql);
		if ($result->num_rows > 0) 
		{
				while($row = $result->fetch_assoc()) 
			{
					return $row["saldo"];
				}
		}
		else 
		{
				return -1;
		}
		$conn->close();
	}
	else
	{
		return;
	}
	
}

function transfer($user_id, $nilai)
{
	if(quorum()>=5)
	{
		$servername = "localhost";
		$username = "root";
		$password = "Sisdis";
		$dbname = "mydb";
		$saldo=0;
		$cek =false;
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error)
		{
				die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT user_id FROM Register";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				if($row["user_id"]==$user_id)
				{
					$cek = true;
				}
			}
			
		}
		if($cek)
		{
			$sql = "UPDATE Saldo SET saldo=saldo+$nilai  WHERE user_id='$user_id' ";
			if (mysqli_query($conn, $sql)) 
			{
					echo "Successfully update saldo";
				return 0;
			} 
			else 
			{
					echo "Error updating record: " . mysqli_error($conn);
				return -1;
			}
		}
		else
		{
			return -1;
		}
		$conn->close();
	}
	else
	{
		return;
	}

}

ini_set("soap.wsdl_cache_enabled", "0"); 

$server = new SoapServer("wsdl", array("soap_version" => SOAP_1_2));
$server->addFunction("ping");
$server->addFunction("register");
$server->addFunction("getSaldo");
$server->addFunction("transfer");
$server->addFunction("getTotalSaldo");
$server->handle();

?>
