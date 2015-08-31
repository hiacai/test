<?php

$signture_data = "";
$signture = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $signture_data = $_POST["data"];
  $signture = $_POST["sign"];
}

//google app Ìá¹©µÄpublic key
$public_key = "
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


$public_key_handle = openssl_get_publickey($public_key);
$result = openssl_verify($signture_data, base64_decode($signture), $public_key_handle, OPENSSL_ALGO_SHA1);
echo $result;
?>