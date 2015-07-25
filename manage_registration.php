<?php

include 'gis_reg_process.php';
$cookie_name = 'aiesec_'.$_POST["program"];
//var_dump($_POST);

$fields = array(
    'first_name' => urlencode(htmlspecialchars($_POST['first_name'])),
    'last_name' => urlencode(htmlspecialchars($_POST['last_name'])),
    'email' => urlencode(htmlspecialchars($_POST['email'])),
    'phone_number' => urlencode(htmlspecialchars($_POST['phone_number'])),
    'utm_source' => urlencode(htmlspecialchars($_POST['utm_source'])),
    'utm_medium' => urlencode(htmlspecialchars($_POST['utm_medium'])),
    'utm_campaign' => urlencode(htmlspecialchars($_POST['utm_campaign'])),
    'program' => urlencode(htmlspecialchars($_POST['program'])),
    'lc' => urlencode(htmlspecialchars($_POST['lc'])),
    'localcommittee' => urlencode($_POST['localcommittee'])
);

if(isset($_COOKIE[$cookie_name])) {
    $string = file_get_contents("config.json");
    $config = json_decode($string, true);
	
    $cookie = $_COOKIE[$cookie_name];
    $cookie = stripslashes($cookie);
    $cookie_values = json_decode($cookie, true);
    //print_r('<pre>');
    //print_r($cookie_values);
    //print_r('</pre>');
    //var_dump($cookie_values);
    $utm_source = $cookie_values['utm_source'];
    $utm_medium = $cookie_values['utm_medium'];
    $utm_campaign = $cookie_values['utm_campaign'];
    $bucket = $cookie_values['bucket'];
    $lc = $cookie_values['lc'];
    $uniqid = $cookie_values['uniqid'];
    $registered = $cookie_values['registered'];
    if($registered!=1){
        $url = $config['api_host'].'/api/v1/register-lead?uniqid='
        	.$uniqid.'&first_name='
        	.$fields['first_name'].'&last_name='
        	.$fields['last_name'].'&email='
        	.$fields['email'].'&phone_number='
        	.$fields['phone_number'].'&lc_form='
        	.$fields['localcommittee'];
        //var_dump($url);
    	$crl = curl_init($url);

    	$headr = array();
    	$accesstoken = $config['api_auth_token'];
    	$headr[] = 'X-Authorization: '.$accesstoken;

    	curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
    	curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);

    	$rest = curl_exec($crl);

    	curl_close($crl);

        $cookie_value = array(
            'utm_source' => $utm_source,
            'utm_medium' => $utm_medium,
            'utm_campaign' => $utm_campaign,
            'bucket' => $bucket,
            'uniqid' => $uniqid,
            'registered' => 1,
            'lc' => $lc);
        $json = json_encode($cookie_value);
        setcookie($cookie_name, $json, time() + (86400 * 60), "/"); // 86400 = 1 day

    	print_r($rest);
    } else{
        registerNewLead($fields);
    }

} else{
	registerNewLead($fields);
}

function registerNewLead($fields)
{
    $string = file_get_contents("config.json");
    $config = json_decode($string, true);
 
    $url = $config['api_host'].'/api/v1/register-new?first_name='
        .$fields['first_name'].'&last_name='
        .$fields['last_name'].'&email='
        .$fields['email'].'&phone_number='
        .$fields['phone_number'].'&lc_form='
        .$fields['localcommittee'].'&utm_source='
        .$fields['utm_source'].'&utm_medium='
        .$fields['utm_medium'].'&utm_campaign='
        .$fields['utm_campaign'].'&program='
        .$fields['program'].'&bucket='
        .$fields['bucket'].'&lc='
        .$fields['lc'];
    //var_dump($url);
    $crl = curl_init($url);

    $headr = array();
    $accesstoken = $config['api_auth_token'];
    $headr[] = 'X-Authorization: '.$accesstoken;

    curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);

    $rest = curl_exec($crl);

    curl_close($crl);

    print_r($rest);
}
?>