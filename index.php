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
}else if($command === 'register'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(isset($_GET['password'])){
		$password = $_GET['password'];
	}
	if(isset($_GET['mail'])){
		$mail = $_GET['mail'];
	}
	if(isset($_GET['username'])){
		$username = $_GET['username'];
	}
	if(isset($_GET['telephone'])){
		$telephone = $_GET['telephone'];
	}
	if(isset($_GET['address'])){
		$address = $_GET['address'];
	}
	if((!isset($account)) or (!isset($password)) or (!isset($mail)) or (!isset($username)) or (!isset($telephone)) or (!isset($address))){
		$result = 'register_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT id FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY id DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'register_same_account';
		}
	}
	
	$id = '0';
	$random = '0';
	
	if(!isset($result)){
		$statement = $connect->query('INSERT INTO register VALUES (\''.$id.'\',\''.$account.'\',\''.$password.'\',\''.$random.'\',\''.$mail.'\',\''.$username.'\',\''.$telephone.'\',\''.$address.'\')');
		$result = 'register_success';
		$session = session_id();
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