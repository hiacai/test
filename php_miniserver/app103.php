<?php
/*
 * Todo: 消耗钻石数量
 * Author: Linwencai
 */
function costValue($uuid, $key, $value) {
	include 'db.php';
	$oldValue = $db->fn_getValue("tb_user", $uuid, $key);
	if ($value > $oldValue)
		return "no_".$key;
	else
		return $db->fn_costValue("tb_user", $uuid, $key, $value);
	$db->close();
}

if (isset($_POST['uuid']) and isset($_POST['value']))
{
	$uuid = $_POST['uuid'];
	$value = abs((int)$_POST['value']);
	if (isset($_POST['key']))
		$key = $_POST['key'];
	else
		$key = "jewel";
	echo costValue($uuid, $key, $value);
}
else if (isset($_GET['uuid']) and isset($_GET['value']))
{
	$uuid = $_GET['uuid'];
	$value = abs((int)$_GET['value']);
	if (isset($_GET['key']))
		$key = $_GET['key'];
	else
		$key = "jewel";
	echo costValue($uuid, $key, $value);
}
else
{
	echo "error";
}
?>