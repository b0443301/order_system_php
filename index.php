<?php

if(isset($_GET['session'])){
	session_id($_GET['session']);
}

session_start();

if(isset($_GET['command'])){
	$command = $_GET['command'];
}else{
	$command = '';
}

if ($command === 'version'){
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	$statement = $connect->query('SELECT version FROM global ORDER BY id DESC LIMIT 1');
	foreach($statement as $row){
		$jsonArray = array($command => $row[$command]);
		echo json_encode($jsonArray);
	}
}else if($command === 'login'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(isset($_GET['password'])){
		$password = $_GET['password'];
	}
	if(!isset($account)){
		$account = '';
	}
	if(!isset($password)){
		$password = '';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	$statement = $connect->query('SELECT id FROM register WHERE account = '.'\''.$account.'\''.' AND password = '.'\''.$password.'\''.' ORDER BY id DESC LIMIT 1');
	foreach($statement as $row){
		$result = 'login_success';
		$session = session_id();
	}
	
	if(!isset($result)){
		$statement = $connect->query('SELECT id FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY id DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'login_fail';
		}
	}
	
	if(!isset($result)){
		$result = 'login_need_register';
	}
	
	if(isset($session)){
		$jsonArray = array('result' => $result, 'session' => $session);
		echo json_encode($jsonArray);
	}else{
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}
}

?>