<?php

if(isset($_GET['command'])){
	$command = $_GET['command'];
}else{
	$command = '';
}

if ($command === 'version'){
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	$statement = $connect->query('CALL GetVersion()');
	foreach($statement as $row){
		$jsonArray = array($command => $row[$command]);
		echo json_encode($jsonArray);
	}
}

?>