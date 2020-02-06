<?php

$db_username = 'root';
$db_password = '';
$db_database = 'phonebook';
$max_file_size = '50240000'; // byte
$upload_url = 'uploads';

$conect = @mysql_connect('127.0.0.1',$db_username,$db_password);
	if (!$conect) {
		die('Could not connect: ' . mysql_error());
	}
$sql_conect = mysql_select_db($db_database, $conect);
mysql_set_charset('utf-8',$sql_conect);

?>