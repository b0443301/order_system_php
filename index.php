﻿<?php

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
	$statement = $connect->query('SELECT version FROM global ORDER BY gid DESC LIMIT 1');
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
	if((!isset($account)) or (!isset($password))){
		$result = 'login_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' AND password = '.'\''.$password.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'login_success';
			$session = session_id();
		}
		if(!isset($result)){
			$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
			foreach($statement as $row){
				$result = 'login_fail';
			}
			if(!isset($result)){
				$result = 'login_need_register';
			}
		}
	}
	
	if($result === 'login_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'login_success'){
		$jsonArray = array('result' => $result, 'session' => $session);
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
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'register_same_account';
		}
		if(!isset($result)){
			$rid = '0';
			$random = '0';
			$statement = $connect->query('INSERT INTO register VALUES (\''.$rid.'\',\''.$account.'\',\''.$password.'\',\''.$random.'\',\''.$mail.'\',\''.$username.'\',\''.$telephone.'\',\''.$address.'\')');
			$result = 'register_success';
			$session = session_id();
		}
	}
	
	
	if($result === 'register_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'register_same_account'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'register_success'){
		$jsonArray = array('result' => $result, 'session' => $session);
		echo json_encode($jsonArray);
	}
}else if($command === 'select_user'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(!isset($account)){
		$result = 'select_user_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT * FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'select_user_success';
			$mail = $row['mail'];
			$username = $row['username'];
			$telephone = $row['telephone'];
			$address = $row['address'];
		}
		if(!isset($result)){
			$result = 'select_user_not_found';
		}
	}
	
	if($result === 'select_user_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if ($result === 'select_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'select_user_success'){
		$jsonArray = array('result' => $result, 'mail' => $mail, 'username' => $username, 'telephone' => $telephone, 'address' => $address);
		echo json_encode($jsonArray);
	}
}else if($command === 'update_user'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
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
	if((!isset($account)) or (!isset($mail)) or (!isset($username)) or (!isset($telephone)) or (!isset($address))){
		$result = 'update_user_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'update_user_success';
			$statement = $connect->query('UPDATE register SET mail = '.'\''.$mail.'\','.' username = '.'\''.$username.'\','.' telephone = '.'\''.$telephone.'\','.' address = '.'\''.$address.'\''.' WHERE account = '.'\''.$account.'\'');
		}
	}
	
	if(!isset($result)){
		$result = 'update_user_not_found';
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}
	else if($result === 'update_user_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'update_user_success'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}
}else if($command === 'select_store'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(!isset($account)){
		$result = 'select_store_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$result = 'select_store_success';
			$rid = $row['rid'];
			$storename = '';
			$telephone = '';
			$address = '';
			$statement = $connect->query('SELECT * FROM store WHERE rid = '.'\''.$rid.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$storename = $row['storename'];
				$telephone = $row['telephone'];
				$address = $row['address'];
				$item = [];
				$statement = $connect->query('SELECT * FROM item WHERE sid = '.'\''.$sid.'\'');
				
				foreach($statement as $row){
					array_push($item,$row['itemname']);
					array_push($item,$row['itemprice']);
				}
			}
		}
	}
	
	if(!isset($result)){
		$result = 'select_store_user_not_found';
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_store_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_store_success'){
		$jsonArray = array('result' => $result, 'storename' => $storename, 'telephone' => $telephone, 'address' => $address, 'item' => $item);
		echo json_encode($jsonArray);
	}
}else if($command === 'update_store'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(isset($_GET['storename'])){
		$storename = $_GET['storename'];
	}
	if(isset($_GET['telephone'])){
		$telephone = $_GET['telephone'];
	}
	if(isset($_GET['address'])){
		$address = $_GET['address'];
	}
	if((!isset($account)) or (!isset($storename)) or (!isset($telephone)) or (!isset($address))){
		$result = 'update_store_fail';
	}

	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$result = 'update_store_success';
			$statement = $connect->query('UPDATE store SET storename = '.'\''.$storename.'\','.' telephone = '.'\''.$telephone.'\','.' address = '.'\''.$address.'\''.' WHERE rid = '.'\''.$rid.'\'');
			if(isset($_GET['name']) and isset($_GET['price'])){
				$name = $_GET['name'];
				$price = $_GET['price'];
				$statement = $connect->query('SELECT sid FROM store WHERE rid = '.'\''.$rid.'\''.' ORDER BY sid DESC LIMIT 1');
				foreach($statement as $row){
					$itemid = '0';
					$sid = $row['sid'];
					for($i=0; $i<count($name); $i++){
						$statement = $connect->query('INSERT INTO item VALUES (\''.$itemid.'\',\''.$name[$i].'\',\''.$price[$i].'\',\''.$sid.'\')');
					}
				}
			}
		}
	}
	
	if(!isset($result)){
		$result = 'update_store_user_not_found';
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'update_store_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'update_store_success'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}
}else if($command === 'select_feeder'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(!isset($account)){
		$result = 'select_feeder_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$statement = $connect->query('SELECT sid FROM feeder WHERE rid = '.'\''.$rid.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$statement = $connect->query('SELECT storename FROM store WHERE sid = '.'\''.$sid.'\''.' ORDER BY sid DESC LIMIT 1');
				foreach($statement as $row){
					$result = 'select_feeder_success';
					$store = $row['storename'];
				}
			}
			if(!isset($result)){
				$result = 'select_feeder_no_data';
			}
		}
		if(!isset($result)){
			$result = 'select_feeder_user_not_found';			
		}
	}
	
	if($result === 'select_feeder_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_feeder_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'select_feeder_no_data'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'select_feeder_success'){
		$jsonArray = array('result' => $result, 'store' => $store);
		echo json_encode($jsonArray);
	}
	
}else if($command === 'update_feeder'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(isset($_GET['store'])){
		$store = $_GET['store'];
	}
	if((!isset($account)) or (!isset($store))){
		$result = 'update_feeder_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$statement = $connect->query('SELECT sid FROM store WHERE storename = '.'\''.$store.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$fid = '0';
				$result = 'update_feeder_success';
				$statement = $connect->query('INSERT INTO feeder VALUES (\''.$fid.'\',\''.$rid.'\',\''.$sid.'\')');
			}
			if(!isset($result)){
				$result = 'update_feeder_store_not_found';
			}
		}
		if(!isset($result)){
			$result = 'update_feeder_user_not_found';
		}
	}
	
	if($result === 'update_feeder_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'update_feeder_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'update_feeder_store_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'update_feeder_success'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}
}

?>