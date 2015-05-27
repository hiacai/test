<?php
/*
 * Todo: 获取用户信息
 * Author: Linwencai
 */
if (isset($_POST['uuid']))
	getUserInfo($_POST['uuid']);
else if (isset($_GET['uuid']))
	getUserInfo($_GET['uuid']);
else
	echo "error";

function getUserInfo($uuid) {
	include 'db.php';
	echo json_encode($db->fn_getUser("tb_user", $uuid));
	$db->close();
}
?>