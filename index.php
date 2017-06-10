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
	}else if($result === 'login_need_register'){
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
			$rid = $row['rid'];
			$storename = '';
			$telephone = '';
			$address = '';
			$statement = $connect->query('SELECT * FROM store WHERE rid = '.'\''.$rid.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$result = 'select_store_success';
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
		if(!isset($result)){
			$result = 'select_store_no_data';
		}
	}
	
	if(!isset($result)){
		$result = 'select_store_user_not_found';
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_store_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_store_no_data'){
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
			$statement = $connect->query('SELECT sid FROM store WHERE rid = '.'\''.$rid.'\''.' ORDER BY rid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$result = 'update_store_success';
				$statement = $connect->query('UPDATE store SET storename = '.'\''.$storename.'\','.' telephone = '.'\''.$telephone.'\','.' address = '.'\''.$address.'\''.' WHERE rid = '.'\''.$rid.'\'');
				if(isset($_GET['name']) and isset($_GET['price'])){
					$itemid = '0';
					$name = $_GET['name'];
					$price = $_GET['price'];
					for($i=0; $i<count($name); $i++){
						$statement = $connect->query('INSERT INTO item VALUES (\''.$itemid.'\',\''.$name[$i].'\',\''.$price[$i].'\',\''.$sid.'\')');
					}
				}
			}
			if(!isset($result)){
				$sid = '0';
				$result = 'update_store_new_data';
				$statement = $connect->query('INSERT INTO store VALUES (\''.$sid.'\',\''.$storename.'\',\''.$telephone.'\',\''.$address.'\',\''.$rid.'\')');
				if(isset($_GET['name']) and isset($_GET['price'])){
					$itemid = '0';
					$name = $_GET['name'];
					$price = $_GET['price'];
					for($i=0; $i<count($name); $i++){
						$statement = $connect->query('INSERT INTO item VALUES (\''.$itemid.'\',\''.$name[$i].'\',\''.$price[$i].'\',\''.$sid.'\')');
					}
				}
			}
		}
		if(!isset($result)){
			$result = 'update_store_user_not_found';
		}
	}
	
	if(!isset($result)){
		$result = 'update_store_user_not_found';
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'update_store_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'update_store_new_data'){
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
					$storename = $row['storename'];
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
		$jsonArray = array('result' => $result, 'storename' => $storename);
		echo json_encode($jsonArray);
	}
	
}else if($command === 'update_feeder'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(isset($_GET['storename'])){
		$storename = $_GET['storename'];
	}
	if((!isset($account)) or (!isset($storename))){
		$result = 'update_feeder_fail';
	}
	
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		
	if(!isset($result)){
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$statement = $connect->query('SELECT sid FROM store WHERE storename = '.'\''.$storename.'\''.' ORDER BY sid DESC LIMIT 1');
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
}else if($command === 'select_store_list'){
	$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
	$statement = $connect->query('SELECT storename FROM store');
	
	$storename = [];
	$result = 'select_store_list_no_data';
	foreach($statement as $row){
		$result = 'select_store_list_success';
		array_push($storename, $row['storename']);
	}
	
	if($result === 'select_store_list_no_data'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_store_list_success'){
		$jsonArray = array('result' => $result, 'storename' => $storename);
		echo json_encode($jsonArray);
	}
}else if($command === 'select_item_list'){
	if(isset($_GET['storename'])){
		$storename = $_GET['storename'];
	}
	if(!isset($storename)){
		$result = 'select_item_list_fail';
	}
	
	if(!isset($result)){
		$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		$statement = $connect->query('SELECT sid FROM store WHERE storename = '.'\''.$storename.'\''.' ORDER BY sid DESC LIMIT 1');
		foreach($statement as $row){
			$sid = $row['sid'];
			$result = 'select_item_list_no_data';
			$item = [];
			$statement = $connect->query('SELECT * FROM item WHERE sid = '.$sid);
			foreach($statement as $row){
				$result = 'select_item_list_success';
				array_push($item,$row['itemname']);
				array_push($item,$row['itemprice']);
			}
		}
		if(!isset($result)){
			$result = 'select_item_list_store_not_found';
		}
	}
	
	if($result === 'select_item_list_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_item_list_no_data'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_item_list_store_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_item_list_success'){
		$jsonArray = array('result' => $result, 'item' => $item);
		echo json_encode($jsonArray);
	}
}else if($command === 'order_dinner'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(isset($_GET['storename'])){
		$storename = $_GET['storename'];
	}
	if(isset($_GET['item'])){
		$item = $_GET['item'];
	}
	if(isset($_GET['number'])){
		$number = $_GET['number'];
	}
	if((!isset($account)) or (!isset($storename)) or (!isset($item)) or (!isset($number)) or (count($item) !== count($number))){
		$result = 'order_dinner_fail';
	}
	
	if(!isset($result)){
		$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$statement = $connect->query('SELECT sid FROM store WHERE storename = '.'\''.$storename.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$itemid = [];
				for($i=0; $i<count($item); $i++){
					$statement = $connect->query('SELECT itemid FROM item WHERE itemname = '.'\''.$item[$i].'\''.' and sid = '.'\''.$sid.'\''.' ORDER BY itemid DESC LIMIT 1');
					foreach($statement as $row){
						array_push($itemid,$row['itemid']);
					}
				}
				if(count($item) !== count($itemid)){
					$result = 'order_dinner_item_not_found';
				}
				if(!isset($result)){
					$result = 'order_dinner_success';
					$did = '';
					$success = False;
					for($i=0; $i<count($itemid); $i++){
						$statement = $connect->query('INSERT INTO dinner VALUES (\''.$did.'\',\''.$rid.'\',\''.$sid.'\',\''.$itemid[$i].'\',\''.$number[$i].'\',\''.$success.'\')');
					}
				}
			}
			if(!isset($result)){
				$result = 'order_dinner_store_not_found';
			}
		}
		if(!isset($result)){
			$result = 'order_dinner_user_not_found';
		}
	}
	
	if($result === 'order_dinner_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'order_dinner_item_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'order_dinner_store_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'order_dinner_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}else if($result === 'order_dinner_success'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);
	}
}else if($command === 'select_dinner_for_order'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(!isset($account)){
		$result = 'select_dinner_for_order_fail';
	}
	
	if(!isset($result)){
		$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$sid = [];
			$itemid = [];
			$number = [];
			$statement = $connect->query('SELECT * FROM dinner WHERE rid = '.'\''.$rid.'\''.' and success = false');
			foreach($statement as $row){
				$result = 'select_dinner_for_order_success';
				array_push($sid,$row['sid']);
				array_push($itemid,$row['itemid']);
				array_push($number,$row['number']);
			}
			if(!isset($result)){
				$result = 'select_dinner_for_order_no_data';
			}else{
				$storename = [];
				$itemname = [];
				foreach($sid as $e){
					$statement = $connect->query('SELECT storename FROM store WHERE sid = '.'\''.$e.'\''.' ORDER BY sid DESC LIMIT 1');
					foreach($statement as $row){
						array_push($storename,$row['storename']);
					}
				}
				foreach($itemid as $e){
					$statement = $connect->query('SELECT itemname FROM item WHERE itemid = '.'\''.$e.'\''.' ORDER BY itemid DESC LIMIT 1');
					foreach($statement as $row){
						array_push($itemname,$row['itemname']);
					}
				}
			}
		}
		if(!isset($result)){
			$result = 'select_dinner_for_order_user_not_found';
		}
	}
	
	if($result === 'select_dinner_for_order_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_dinner_for_order_no_data'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_dinner_for_order_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_dinner_for_order_success'){
		$jsonArray = array('result' => $result, 'storename' => $storename, 'itemname' => $itemname, 'number' => $number);
		echo json_encode($jsonArray);
	}
}else if($command === 'select_dinner_for_store'){
	if(isset($_GET['account'])){
		$account = $_GET['account'];
	}
	if(!isset($account)){
		$result = 'select_dinner_for_store_fail';
	}
	
	if(!isset($result)){
		$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$account.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){
			$rid = $row['rid'];
			$statement = $connect->query('SELECT sid FROM store WHERE rid = '.'\''.$rid.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$ridList = [];
				$itemid = [];
				$number = [];
				$statement = $connect->query('SELECT * FROM dinner WHERE sid = '.'\''.$sid.'\''.' and success = false');
				foreach($statement as $row){
					$result = 'select_dinner_for_store_success';
					array_push($ridList,$row['rid']);
					array_push($itemid,$row['itemid']);
					array_push($number,$row['number']);
				}
				if(!isset($result)){
					$result = 'select_dinner_for_store_no_data';
				}else{
					$account = [];
					$itemname = [];
					foreach($ridList as $e){
						$statement = $connect->query('SELECT account FROM register WHERE rid = '.'\''.$e.'\''.' ORDER BY rid DESC LIMIT 1');
						foreach($statement as $row){
							array_push($account,$row['account']);
						}
					}
					foreach($itemid as $e){
						$statement = $connect->query('SELECT itemname FROM item WHERE itemid = '.'\''.$e.'\''.' ORDER BY itemid DESC LIMIT 1');
						foreach($statement as $row){
							array_push($itemname,$row['itemname']);
						}
					}
				}	
						
			}
			if(!isset($result)){
				$result = 'select_dinner_for_store_store_not_found';
			}
		}
		if(!isset($result)){
			$result = 'select_dinner_for_store_user_not_found';
		}
	}
	
	if($result === 'select_dinner_for_store_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'select_dinner_for_store_no_data'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_dinner_for_store_store_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_dinner_for_store_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'select_dinner_for_store_success'){
		$jsonArray = array('result' => $result, 'account' => $account, 'itemname' => $itemname, 'number' => $number);
		echo json_encode($jsonArray);
	}
}else if($command === 'checkout_dinner_for_store'){
	if(isset($_GET['useraccount'])){
		$useraccount = $_GET['useraccount'];
	}
	if(isset($_GET['storeaccount'])){
		$storeaccount = $_GET['storeaccount'];
	}
	if(isset($_GET['item'])){
		$item = $_GET['item'];
	}
	if(isset($_GET['number'])){
		$number = $_GET['number'];
	}
	if((!isset($useraccount)) or (!isset($storeaccount)) or (!isset($item)) or (!isset($number))){
		$result = 'checkout_dinner_for_store_fail';
	}
	
	if(!isset($result)){
		$connect = new PDO('mysql:host=localhost;dbname=order_system;charset=utf8', 'osadmin', '0983451956');
		$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$storeaccount.'\''.' ORDER BY rid DESC LIMIT 1');
		foreach($statement as $row){			
			$rid = $row['rid'];
			$statement = $connect->query('SELECT sid FROM store WHERE rid = '.'\''.$rid.'\''.' ORDER BY sid DESC LIMIT 1');
			foreach($statement as $row){
				$sid = $row['sid'];
				$statement = $connect->query('SELECT rid FROM register WHERE account = '.'\''.$useraccount.'\''.' ORDER BY rid DESC LIMIT 1');
				foreach($statement as $row){
					$rid = $row['rid'];
					$statement = $connect->query('SELECT itemid FROM item WHERE itemname = '.'\''.$item.'\''.' AND sid = '.'\''.$sid.'\''.' ORDER BY sid DESC LIMIT 1');
					foreach($statement as $row){
						$itemid = $row['itemid'];
						$statement = $connect->query('SELECT did FROM dinner WHERE rid = '.'\''.$rid.'\''.' AND sid = '.'\''.$sid.'\''.' AND itemid = '.'\''.$itemid.'\''.' AND number = '.'\''.$number.'\''.' AND success = False ORDER BY sid DESC LIMIT 1');
						foreach($statement as $row){
							$did = $row['did'];
							$result = 'checkout_dinner_for_store_success';
							$statement = $connect->query('UPDATE dinner SET success = True WHERE did = '.'\''.$did.'\'');
						}
						if(!isset($result)){
							$result = 'checkout_dinner_for_store_dinner_not_found';
						}						
					}
					if(!isset($result)){
						$result = 'checkout_dinner_for_store_item_not_found';
					}					
				}
				if(!isset($result)){
					$result = 'checkout_dinner_for_store_user_not_found';					
				}			
			}
			if(!isset($result)){
				$result = 'checkout_dinner_for_store_store_not_found';
			}
		}
		if(!isset($result)){
			$result = 'checkout_dinner_for_store_user_not_found';
		}		
	}
	
	if($result === 'checkout_dinner_for_store_fail'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);		
	}else if($result === 'checkout_dinner_for_store_success'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'checkout_dinner_for_store_dinner_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'checkout_dinner_for_store_item_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'checkout_dinner_for_store_user_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}else if($result === 'checkout_dinner_for_store_store_not_found'){
		$jsonArray = array('result' => $result);
		echo json_encode($jsonArray);	
	}
}

?>