<?php
require ('../config.php');

$uid = isset($_REQUEST['uid']) ? trim($_REQUEST['uid']) : false; // Update By Id -> uid
$opr = isset($_REQUEST['opr']) ? $_REQUEST['opr'] : false; // Update By Id -> uid
$firstname = trim(htmlspecialchars($_REQUEST['firstname']));
$lastname = trim(htmlspecialchars($_REQUEST['lastname']));
$phone = trim(htmlspecialchars($_REQUEST['phone']));
$gender = (trim($_REQUEST['gender']) == '') ? '1' : $_REQUEST['gender'];
$soundfile = $_FILES['soundfile'];

$target_dir = '../uploads/';

$Ok = true;

// Input Checks
if( $opr != 'delete')
{ 
	if(empty($firstname) OR empty($lastname) OR empty($phone) /*OR empty($gender)*/ )
	{
		echo json_encode(array('errorMsg'=>'درج نام و نام خانوادگی و شماره تماس الزامی است'));
		$Ok = false;
	} else if (!preg_match("/^[0-9]{7,11}$/", $phone )){
		echo json_encode(array('errorMsg'=>'شماره تماس وارد شده صحیح نیست'));
		$Ok = false;
	}
}

// FileCheck
if ( $Ok == true && !empty($_FILES['soundfile']['name']))
{
	$ext = pathinfo($_FILES['soundfile']['name'],PATHINFO_EXTENSION);
	if($ext != "wave" && $ext != "mp3") 
	{
		echo json_encode(array('errorMsg'=>'برای فایل صوتی فقط فرمت wave و mp3 قابل قبول است. فرمت فایل ارسالی شما '.$ext.' است.'));
		$Ok = false;
	}
	else if ($_FILES["soundfile"]["size"] > $max_file_size) 
	{
		$mb_max_file_size = $max_file_size / 1024000;
		$filesize = round($_FILES["soundfile"]["size"] / 1024000, 2);
		echo json_encode(array('errorMsg'=>'حداکثر حجم قابل قبول برای هر فایل '.$mb_max_file_size.' مگابایت است. حجم فایل ارسالی شما  '.$filesize.' مگابایت است.'));    
		$Ok = false;
	}
	else{
		$target_file = date('YMd-his') .'-'. rand(10000,20000).".".$ext;
		if (!move_uploaded_file($_FILES["soundfile"]["tmp_name"], $target_dir.$target_file)){
			$fileError = $_FILES["soundfile"]["error"]; // where FILE_NAME is the name attribute of the file input in your form
			switch($fileError) {
				case UPLOAD_ERR_INI_SIZE:
					$error = 'حجم فایل ارسالی از حجم قابل قبول توسط PHP شما بیشتر است. لطفا تنظیمات مناسب را در فایل php.ini اعمال فرمایید.';
					// Exceeds max size in php.ini
					break;
				case UPLOAD_ERR_PARTIAL:
					$error = 'حجم فایل ارسالی از مقدار قابل پشتیبانی توسط سرور بیشتر است.';
					// Exceeds max size in html form
					break;
				case UPLOAD_ERR_NO_FILE:
					$error = 'هیچ فایلی به سرور ارسال نشده است';
					// No file was uploaded
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$error = 'سیستم قادر به ارسال فایل به پوشه tmp نیست.';
					// No /tmp dir to write to
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$error = 'امکان ذخیره فایل روی سرور وجود ندارد.';
					// Error writing to disk
					break;
				default:
					$error = 'در ارسال فایل خطایی رخ داده است';
					break;
			}
			echo json_encode(array('errorMsg'=>$error));
			$Ok = false;
		}
	}	
}

if ($uid != false){
	if ($opr == 'update'){
		$sql = "UPDATE `users` SET 
		`firstname`='$firstname' ,
		`lastname`='$lastname' ,
		`gender` = '$gender' ,
		`soundfile` = '$target_file'
		WHERE id=$uid";
		if($_REQUEST['oldphone'] != 'null'){
			$phonesql = "UPDATE `phones` SET
			`phone`='$phone'
			WHERE `user_id` = $uid 
			AND `phone` = $_REQUEST[oldphone]";				
		}else{
			$phonesql = "INSERT INTO phones 
			(`user_id`, `phone`)
			VALUES
			('$uid', '$phone')";
		}
	}else if ($opr == 'delete'){
		$sql = "DELETE FROM users WHERE id=$uid";
	}else{
		echo json_encode(array('errorMsg'=>'عملیات دیتابیس ناشناخته است'));
		$Ok = false;
	}
} else {
	$sql = "INSERT INTO `users` 
			(`firstname`,`lastname`,`gender`,`soundfile`) 
			VALUES 
			('$firstname','$lastname','$gender', '$target_file')";
	$phonesql = "INSERT INTO phones 
			(`user_id`, `phone`)
			VALUES
			(LAST_INSERT_ID(), '$phone')";
}

if ($Ok == true)
{	
	$result = @mysql_query($sql);	
	$gender = ($gender == '0') ? 'خانم' : 'آقای' ;
	if ($result){
		echo json_encode(array(				
			'firstname' => $firstname,
			'lastname' => $lastname,
			'gender' => $gender,
			'soundfile' => $target_file,
			'successMsg' => 'عملیات برای '.$gender. ' '. $firstname . ' ' . $lastname .' با موفقیت انجام شد'
		));
		if($opr == false or isset($_REQUEST['oldphone'])){
			@mysql_query($phonesql);
		}
	}else{
		echo json_encode(array('errorMsg'=>'در انجام عملیات خطایی بوجود آمده است'));
	}	
}

?>