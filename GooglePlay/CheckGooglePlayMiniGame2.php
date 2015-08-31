<?php
/*
 * Todo: 小游戏谷歌充值校验
 * Author: Linwencai
 * 
 */

/*
参数：
data	订单数据
sign	签名
id	游戏id(包名)
payMoney	金额

返回：
-5	异常的公钥	"-5 PublicKey:..."
-4  校验出现异常	"-4 "
-3	未知的游戏id	"-3 Error gameid:test"
-2	找不到订单号	"-2 Not orderid ..."
-1	缺少参数	"-1 Not argument data"
0	校验失败	"0"
1	校验成功	"1"
2	订单号重复	"2 orderid:12999763169054705758.1354549110253183"
3	数据库连接失败	"3 Connect Error (2002)..."
4	数据库其他异常	"4 Errno: 1146 Info:.."
*/

//http://127.0.0.1/CheckGooglePlayMiniGame.php?data={%22orderId%22:%2212999763169054705758.1354549110253183%22,%22packageName%22:%22com.MI.Angelfly%22,%22productId%22:%22angel_dia01%22,%22purchaseTime%22:1419303818706,%22purchaseState%22:0,%22purchaseToken%22:%22pmgjpkgbinkjalobejbkmfoc.AO-J1OzfTqZM1WOAeRpoNTsgx6ZeCYlT-9RoPkGiVW7mgXONssZOO3sfjy8lWVDPElt48q6Ok2XSvp3dGWnKNENmE10tpM_zpIZs20ssKJ-XQn78emYvvv4%22}&sign=LPriNRh6hMvW5n7%2bwCHr%2b0/JEWnwrYCTMOKGu351I5LLRm6T912czaICLP5jInMo%2b%2bX6q8s1iteynfur2eFxPyF/RUsEcnZyYSxB1hOYwMhiKw46c5KWvPKeLJCVnnnfgjW/1kV9aZ/1T3ytg3TM6%2bDOYMa651lkV5gONjE5cAnvyMwfhSqx7w/O2bFZOEi85gQWg1fJoGywrHpJe1l9KlOapmU%2bKHe7X1gvExKdUrggwstO0TotGrLbgVJtZErM85fJzj41yTYIuWC/C0MSX6lVSEJ9C08sz0j4j3rgPVuECYXUoqAhgPgangb5xMM8N3XediJ7QHMU%2bK27SCrVUg==&id=com.MI.Angelfly&payMoney=1

// 数据库配置
$db_host = "localhost";
$db_user = "root";
$db_pwd = "111";
$db_name = "minigame";

// 屏蔽警告
//error_reporting(E_ALL^E_NOTICE^E_WARNING);

//屏蔽警告和mysql_connect即将弃用的信息
error_reporting(E_ALL ^E_NOTICE^E_WARNING ^ E_DEPRECATED);  

//谷歌app公共key
$public_key_plane1 = "
-----BEGIN PUBLIC KEY----- 
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhuj+LsZNFi0QFb+gM3GG
tB6uC4M1a6XfLfAq1e4cAqVeTYQETO7dNufk442OFEH7vBOEzkcXu94ehsrTlQjm
tq5zXFPmIyhGMRXyXb5oikAuMD6xG/yk/F5v6WHDcjI6RddDZIV4rUh4hXuqP7jl
+ceXneEnLJMGFeq33E7Zfgw7eI0f6QWaYIYfOFDIx4dqZ3sUxgxL5eKNJgYXTvKJ
vEgNAS42kKDmAsg6ouXP5CzZzWs5fCDBRzgwvhjTl5gqfzXLpo5HpkV65ApiR8+3
6vW1ZsDp5XH/8PfhZYjHDdDjsfi9F8HJM2g+jWeGruyZIpBvLcXNHvV2CE8wV2oi
CwIDAQAB
-----END PUBLIC KEY----- 
";

$public_key_cuancuansao = "
-----BEGIN PUBLIC KEY----- 
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApmqS6S7hZSHaWNsJXebC
WOp+oiUltXkZnYIbrAqj7ySY3j08MqGinp1rt4UdiQ0bhgfT3pqP77iviGUy0KF+
Ku7EHdTVCz5fxl7Mi20GwuPT02FblczAIgblGcD2DEEHEqG2kPF9lumvCaPpqftR
R1J8crft/+NcCToojTTWs/PWX/NxOjIYghF3n4YxK4IIiQbr73BFUD4nUkHARHHd
u2hcVNf4Eq5/oBlpyyEAX7iSy0R7m/KVvXEK8MJN7A5UOWqAjRqXUJhRuppH5i4l
InyptiakaARHY17oLbQ82RWRGaKlgPro77pZ4UknHda1WOD/+H27i1O9b154n97b
3wIDAQAB
-----END PUBLIC KEY----- 
";

$public_key_array = array(
"com.MI.Angelfly"=>$public_key_plane1,   //飞机1
"com.zhangfei.StickFood"=>$public_key_cuancuansao,  //串串烧
);
//print_r($public_key_array);

//获取传参
$signture_data = "";
$signture = "";
$gameid = "";
$price = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //(array_key_exists("data", $_POST) or array_key_exists("sign", $_POST) or array_key_exists("id", $_POST) or array_key_exists("payMoney", $_POST)) and die("not argument");
    //if (!(array_key_exists("data", $_POST) and array_key_exists("sign", $_POST) and array_key_exists("id", $_POST) and array_key_exists("payMoney", $_POST))){
    //    die("not argument");
    //}
    if (!array_key_exists("data", $_POST)){
        die("-1 Not argument data");
    }
    if (!array_key_exists("sign", $_POST)){
        die("-1 Not argument sign");
    }
    if (!array_key_exists("id", $_POST)){
        die("-1 Not argument id");
    }
    if (!array_key_exists("payMoney", $_POST)){
        die("-1 Not argument payMoney");
    }

    $signture_data = $_POST["data"];
    $signture = $_POST["sign"];
    $gameid = $_POST["id"];
    $price = $_POST["payMoney"];
}


else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //(array_key_exists("data", $_GET) or array_key_exists("sign", $_GET) or array_key_exists("id", $_GET) or array_key_exists("payMoney", $_GET)) and die("not argument");
    //if (!(array_key_exists("data", $_GET) and array_key_exists("sign", $_GET) and array_key_exists("id", $_GET) and array_key_exists("payMoney", $_GET))){
    //    die("not argument");
    //}
    if (!array_key_exists("data", $_GET)){
        die("-1 Not argument data");
    }
    if (!array_key_exists("sign", $_GET)){
        die("-1 Not argument sign");
    }
    if (!array_key_exists("id", $_GET)){
        die("-1 Not argument id");
    }
    if (!array_key_exists("payMoney", $_GET)){
        die("-1 Not argument payMoney");
    }

    $signture_data = $_GET["data"];
    $signture = $_GET["sign"];
    $gameid = $_GET["id"];
    $price = $_GET["payMoney"];
}
else{
    die("-1 Err REQUEST_METHOD");
}
//echo $signture_data . "<br>";
//echo $signture . "<br>";

//($signture_data=="" or $signture=="" or $gameid=="") and die("not argument");

$data_array = json_decode($signture_data, True);
//!array_key_exists("orderId", $data_array) and die("not orderid");
if (!array_key_exists("orderId", $data_array)){
    die("-2 Not orderid " . $signture_data);
}
$orderid = $data_array["orderId"];

if (!array_key_exists($gameid, $public_key_array)){
    die("-3 Error gameid:" . $gameid);
}

$public_key = $public_key_array[$gameid];

//校验签名
$public_key_handle = openssl_get_publickey($public_key) or die("-5 PublicKey:" . $public_key);
$result = @openssl_verify($signture_data, base64_decode($signture), $public_key_handle, OPENSSL_ALGO_SHA1) or die("-4 ");

//echo $result;

//记录数据库
if ($result){
    $sql = "INSERT INTO tb_googlerecord (gameid,orderid,price,time,data,sign) VALUE ('" . $gameid . "', '" . $orderid . "','" . $price . "',UNIX_TIMESTAMP(), '" . $signture_data . "', '" . $signture . "')";
	/*
    try{
        saveToDb($sql,$db_host,$db_user,$db_pwd,$db_name);
    }
    catch(Exception $e){
        echo "5 " . $e;
    }*/

	$extensions = get_loaded_extensions();
	//如果有mysqli扩展，使用mysqli_connect，否则用mysql
	if (in_array("mysqli", $extensions)){
		$mysqli = mysqli_connect($db_host,$db_user,$db_pwd,$db_name);
        if (mysqli_connect_error()) {
            die("3 Connect Error (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
        }
        $db_result = mysqli_query($mysqli, $sql);
		if (!$db_result){
			$errno = mysqli_errno($mysqli);
			if ($errno == 1062){
				echo "2 orderid:" . $orderid;
			}
			else{
				echo "4 Errno: " . $errno . " Info: " . mysqli_error($mysqli);
			}
		}
		else{
			echo 1;
		}
        $mysqli->close();
    }
    elseif (in_array("mysql", $extensions)){
        $link=mysql_connect($db_host,$db_user,$db_pwd) or die ("3  " . mysql_error());
        mysql_select_db($db_name,$link) or die("3 NoDatabase: " . $db_name);
        $db_result = mysql_query($sql);
		if (!$db_result){
			$errno = mysql_errno();
			if ($errno == 1062){
				echo "2 orderid:" . $orderid;
			}
			else{
				echo "4 Errno: " . $errno . " Info: " . mysql_error();
			}
		}
		else{
			echo 1;
		}
        mysql_close($link);
    }
    else{
        die("3 PHP not mysqli or mysql");
    }
    //echo $db_result ? $db_result : "2 orderid:" . $orderid . " time:" . $data_array["purchaseTime"];
    //echo $db_result ? $db_result : "2 orderid:" . $orderid;
}
else{
    echo $result;
}
?>