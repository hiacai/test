<?php
/*
 * Todo: google play 充值
 * Author: Linwencai
 */
$signture_string = "";
$signture = "";
$uuid = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $signture_string = $_POST["data"];
  $signture = $_POST["sign"];
  $uuid = $_POST["uuid"];
}

/* test
$uuid = 1;
$signture = "LPriNRh6hMvW5n7+wCHr+0/JEWnwrYCTMOKGu351I5LLRm6T912czaICLP5jInMo++X6q8s1iteynfur2eFxPyF/RUsEcnZyYSxB1hOYwMhiKw46c5KWvPKeLJCVnnnfgjW/1kV9aZ/1T3ytg3TM6+DOYMa651lkV5gONjE5cAnvyMwfhSqx7w/O2bFZOEi85gQWg1fJoGywrHpJe1l9KlOapmU+KHe7X1gvExKdUrggwstO0TotGrLbgVJtZErM85fJzj41yTYIuWC/C0MSX6lVSEJ9C08sz0j4j3rgPVuECYXUoqAhgPgangb5xMM8N3XediJ7QHMU+K27SCrVUg==";
$signture_string = '{"orderId":"ord2erId","packageName":"com.MI.Angelfly","productId":"angel_dia01","purchaseTime":1419303818706,"purchaseState": 0,"purchaseToken": "pmgjpkgbinkjalobejbkmfoc.AO-J1OzfTqZM1WOAeRpoNTsgx6ZeCYlT-9RoPkGiVW7mgXONssZOO3sfjy8lWVDPElt48q6Ok2XSvp3dGWnKNENmE10tpM_zpIZs20ssKJ-XQn78emYvvv4"}';
*/
include "CheckGooglePlay.php";
$signture_json = json_decode($signture_string);
$signture_data = object_array($signture_json);
$orderId = $signture_data["orderId"];


# 检查订单是否重复
if (checkOrderId($orderId))
{
	echo "Repeat";
}
# 检查订单是否有效
else if (!checkGooglePlay($signture_string, $signture) and 0)
{	
	echo "error";
}
else
{
	//充值的价格
	$productId = $signture_data["productId"];
	$price = $config_array["google_billing"][$productId];

	//给予钻石
	$result = $db->fn_addValue("tb_user", $uuid, "jewel", $price);
	
	if ($result != 0)
	{
		//订单写入数据库
		$now = date(DATE_W3C);
		$db->fn_insert("tb_bill", "orderId,uuid,data,signature,date", "'$orderId','$uuid','$signture_string','$signture','$now'");
	
		//返回新的钻石数
		echo $result;
	}
	else
		echo "error";

}

$db->close();
?>