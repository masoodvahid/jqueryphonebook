<?php
require('../config.php');
//$uid  = $_REQUEST['uid'];
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'uid';
$order= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
$offset = ($page-1)*$rows;
$result = array();
$users = array();
$where = '';

// If searched anything...
if (isset($_POST['firstname']) or isset($_POST['lastname']) or isset($_POST['phone']))
{
	$firstname = isset($_POST['firstname']) ? mysql_real_escape_string($_POST['firstname']) : '';
	$lastname = isset($_POST['lastname']) ? mysql_real_escape_string($_POST['lastname']) : '';
	$phone = isset($_POST['phone']) ? mysql_real_escape_string($_POST['phone']) : '';
	if(!empty($_POST['phone'])){
		$where = "WHERE firstname LIKE '%$firstname%' AND lastname LIKE '%$lastname%' AND phone LIKE '%$phone%'";						
	}else{
		$where = "WHERE firstname LIKE '%$firstname%' AND lastname LIKE '%$lastname%'";
	}	
}

// Count total records for pagination
$count = mysql_query("SELECT COUNT(*) FROM users $where");
$total = mysql_fetch_row($count);
$result["total"] = $total[0];
// ORDER BY $sort $order 
// Fetch and prepare results
$results = mysql_query("SELECT 
						users.id as uid, 
						firstname,
						lastname,
						gender,
						soundfile,
						phones.id AS pid,
						phone,
						note
						FROM users 
						LEFT JOIN phones 
						ON users.id = phones.user_id 
						". $where ."
						GROUP BY phones.user_id
						ORDER BY $sort $order 
						LIMIT $offset,$rows") 
						or die ('Error'.mysql_error());		

while($user = mysql_fetch_object($results)){	
	array_push($users, $user);
}
$result["rows"] = $users;

// Send Results to view
echo json_encode($result);
?>