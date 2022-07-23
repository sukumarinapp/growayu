<?php
    date_default_timezone_set("Asia/Kolkata");
	
    $mysql_hostname = "localhost";
	$mysql_user = "root";
    $mysql_password = "1bitmysql";
    $mysql_database = "hmi_clinic";

    $conn = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

    $base_url = "http://localhost/clinic_hctsol/";
	#$base_url = "http://52.76.39.103/clinic/";
    //$base_url = "http://157.245.111.165/clinic/";
    $clinic_token = "5836d45ea6571188bbfadfa51c14da08";

    function send_sms($msg,$mno)
    {
        $params = array(
            "user" => "vikasonebit",
            "pass" => "pretty777",
            "sender" => "SPOTTX",
            "phone" => $mno,
            "text" =>  $msg,
            "priority"=> "ndnd",
            "stype"=> "normal",
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://bhashsms.com/api/sendmsg.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $sms_response = curl_exec ($ch);
        curl_close ($ch);

    }

