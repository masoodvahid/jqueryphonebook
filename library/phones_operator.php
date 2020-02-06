<?php
require('../config.php');
$uid  = $_REQUEST['uid'];

if ($_REQUEST['opr'] == 'view')

{
	$phones = array();	
	$res = mysql_query("SELECT * FROM phones WHERE user_id = $uid ");		
	while($phone = mysql_fetch_object($res)){	
		array_push($phones, $phone);		
	}
	$result["rows"] = $phones;	
	echo json_encode($result);
} 
else if ($_REQUEST['opr'] == 'edit')
{
	$phone = htmlspecialchars(trim($_REQUEST['phone']));
	$type = htmlspecialchars(trim($_REQUEST['type']));
	$note = empty($_REQUEST['note']) ? '' : htmlspecialchars(trim($_REQUEST['note']));
	if (empty($phone) or !preg_match("/^[0-9]{7,11}$/", $phone )) {
		$result['Ok'] = false;			
		$result['msg'] = 'شماره تماس صحیح نیست';
		
	}else{
		// Update Exist Records
		if (!empty($_REQUEST['pid'])){
			$pid = $_REQUEST['pid'];								
			mysql_query("UPDATE phones SET `phone`='$phone', `phone_type`='$type', `note`= '$note', `updated_at`=now() WHERE id = $pid");
			$result['Ok'] = true;
			$result['title'] = 'بروزرسانی';
			$result['msg'] = 'شماره تماس با موفقیت بروز رسانی گردید.';
		}else{
			// Add New Records
			$uid = $_REQUEST['uid'];
			mysql_query("INSERT INTO phones (`user_id`, `phone`, `phone_type`, `note`, `updated_at`) values ('$uid','$phone','$type','$note',now())") or die(mysql_error());
			$result['Ok'] = true;
			$result['title'] = 'رکورد جدید';
			$result['msg'] = 'شماره تماس با موفقیت اضافه شد.';
		}
	}
	echo json_encode($result);		
}
else if ($_REQUEST['opr'] == 'delete') {
	$pid = $_REQUEST['pid'];
	mysql_query("DELETE FROM phones WHERE id = $pid");
	$result['Ok'] = true;	
	$result['msg'] = 'شماره با موفقیت حذف شد.';
	echo json_encode($result);
}
else
{
	echo 'درخواست نامشخص';
}

?>