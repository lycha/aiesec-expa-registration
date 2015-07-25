<?php
/*
Plugin Name: AIESEC EXPA  
Description: Plugin based on gis_curl_registration script by Dan Laush upgraded to Wordpress plugin
Version: 0.2
Author: Krzysztof Jackowski
Author URI: https://www.linkedin.com/profile/view?id=202008277&trk=nav_responsive_tab_profile_pic
License: GPL 
*/
wp_enqueue_script('jquery');
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

// [expa-form program="gt"]

function expa_form( $atts ) {
    $a = shortcode_atts( array(
        'program' => '',
    ), $atts );
    
    echo wp_enqueue_style( 'style-name', plugins_url('style.css', __FILE__ ));
        
    $form = file_get_contents('form.html',TRUE);
    $uniqid = uniqid();
    $utm_source = $_GET["utm_source"];
    $utm_medium = $_GET["utm_medium"];
    $utm_campaign = $_GET["utm_campaign"];
    $bucket = $_GET["bucket"];
    $lc = $_GET["lc"];

    if($bucket==""){
        $bucket = "n/d";   
    }
    //check if lead parameters where provided if not set to generic
    if($utm_source==""){
        $utm_source = "generic";   
    }
    if($utm_medium==""){
        $utm_medium = "generic";   
    }
    if($utm_campaign==""){
        $utm_campaign = "generic";   
    }

    ///////Get EXPA Leads from MKT Tracking Tool API/////////////////
    $string = file_get_contents(plugins_url('config.json', __FILE__ ));
    $config = json_decode($string, true);
    $url = $config['api_host'].'/api/v1/get-expa-leads';
    $crl = curl_init($url);
    $headr = array();
    $accesstoken = $config['api_auth_token'];
    $headr[] = 'X-Authorization: '.$accesstoken;
    curl_setopt($crl, CURLOPT_HTTPHEADER,$headr);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($crl);
    curl_close($crl);
    $leads = json_decode($res); 
    //var_dump($leads);
    $option_list = "";
    foreach($leads as $key => $value){
        $option_list = $option_list.'<option value="'.$value->expa_name.'">'.$value->keywords.'</option>'."\n";//var_dump($lead->);    
    }
    //////////////////////////////////////////////////////////////////////

    ////////////Replace elements in form.html////////////////////////////
    $form = str_replace("{utm_source}",$utm_source,$form);
    $form = str_replace("{utm_medium}",$utm_medium,$form);
    $form = str_replace("{utm_campaign}",$utm_campaign,$form);
    $form = str_replace("{bucket}",$bucket,$form);
    $form = str_replace("{uniqid}",$uniqid,$form); 
    $form = str_replace("{program}",$a['program'],$form);
    $form = str_replace("{path-gis_reg_process}",plugins_url('gis_reg_process.php', __FILE__ ),$form);
    $form = str_replace("{path-gis_lcMapper}",plugins_url('gis_lcMapper.js', __FILE__ ),$form);
    $form = str_replace("{path-leads-json}",plugins_url('leads.json', __FILE__ ),$form);
    $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $form = str_replace("{website_url}",$actual_link,$form);
    $form = str_replace("{leads-option-list}",$option_list,$form);
    $form = str_replace("{lc}",$lc,$form);
    $form = str_replace("{path-manage_registration}",plugins_url('manage_registration.php', __FILE__ ),$form);
    $form = str_replace("{path-manage_leads}",plugins_url('manage_leads.php', __FILE__ ),$form);
    
    
    if($_GET["thank_you"]==="true"){
        return "<p>Dziękujemy bardzo za rejestrację. Wkrótce dostaniesz maila z potwierdzeniem założenia konta. Powodzenia w Twojej podróży do kariery!</p>"; 
    } elseif ($_GET["error"]!=""){
        
        $form = str_replace('<div id="error" class="error"><p></p></div>','<div id="error" class="error"><p>'.$_GET["error"].'</p></div>',$form);
        return $form;    
    }
    //var_dump( plugins_url('gis_reg_process.php', __FILE__ ));
    return $form;
}
add_shortcode( 'expa-form', 'expa_form' );

?>
