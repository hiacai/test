<?php
/*
 * Todo: 获取钻石数量
 * Author: Linwencai
 */
function getValue($uuid, $key) {
	include 'db.php';
	//return json_encode($db->fn_getValue("tb_user", $uuid, $key));
	$value = $db->fn_getValue("tb_user", $uuid, $key);
	if ($value != -1)
		return $value;
	else
		return "error";
	$db->close();
}

if (isset($_POST['uuid']))
{
	$uuid = $_POST['uuid'];
	if (isset($_POST['key']))
		$key = $_POST['key'];
	else
		$key = "jewel";
	echo getValue($uuid, $key);
}
else if (isset($_GET['uuid']))
{
	$uuid = $_GET['uuid'];
	if (isset($_GET['key']))
		$key = $_GET['key'];
	else
		$key = "jewel";
	echo getValue($uuid, $key);
}
else
{
	echo "error";
}
?>