<?php
require('../config.php');
$uid  = $_REQUEST['uid'];


if ($_REQUEST['opr'] == 'view'){
	$phones = array();
	// Count total records for pagination
	$count = mysql_query("SELECT COUNT(*) FROM phones WHERE user_id = $uid ");
	$total = mysql_fetch_row($count);
	$result["total"] = $total[0];
	// ORDER BY $sort $order 
	// Fetch and prepare results
	$res = mysql_query("SELECT * FROM phones WHERE user_id = $uid ");		
	while($phone = mysql_fetch_object($res)){	
		array_push($phones, $phone);
		//var_dump($phone);
	}
	$result["rows"] = $phones;
	//var_dump($result);
	//echo "Nothing happend";
	// Send Results to view
	echo json_encode($result);
} else if ($_REQUEST['opr'] == 'edit'){
	$pid = $_REQUEST['id'];
	$phone = trim(htmlspecialchars($_REQUEST['phone']));
	$note = ($_REQUEST['note']=='null') ? '' : trim(htmlspecialchars($_REQUEST['note']));
	echo $phone.'|'.$note.'|'.$pid;
	if (!empty($phone)){
		mysql_query("UPDATE phones SET phone = '$phone' , note = '$note' WHERE id = $pid") or die (mysql_error());
		echo 'byebye';
	}else{
		mysql_query("DELETE FROM phones WHERE id=$pid") or die (mysql_error());
		echo 'not accepted';
	}	
}

?>