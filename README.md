/*
Plugin Name: AIESEC EXPA Registration 
Description: Plugin based on gis_curl_registration script by Dan Laush upgraded to Wordpress plugin and conected with Marketing Tracking Tool.
Version: 0.2
Author: Krzysztof Jackowski
Author URI: https://www.linkedin.com/profile/view?id=202008277&trk=nav_responsive_tab_profile_pic
License: GPL 
*/

Plugin was created to manage OGX registrations. Form is connected to EXPA (internships.aiesec.org), MKT Tracking tool (http://aiesecpl.home.pl/mkt-tracking/public) and GT/GC Tracking tool in Google Spreadsheet. Each registration is saved in EXPA and internal Database via MKT Tracking Tool API. 
	
	|--------------------------------------------------------------------------
	| config.json
	|--------------------------------------------------------------------------
	|
	| Here are saved api information to connect with Marketing tool and National Office data from EXPA
	|
	
	|--------------------------------------------------------------------------
	| form.html
	|--------------------------------------------------------------------------
	|
	| Form template with basic javascript to manage it. Template is updated 
	| based on information recived by plugin. All the variables are encapsulated with {} brackets
	|

	|--------------------------------------------------------------------------
	| gis_reg_process.php
	|--------------------------------------------------------------------------
	|
	| Script to manage EXPA registration via CURL. 
	|

	|--------------------------------------------------------------------------
	| manage_leads.php
	|--------------------------------------------------------------------------
	|
	| Script executed when lead opens website. Saves cookie file with lead information 
	| and updates database via API
	|

	
	|--------------------------------------------------------------------------
	| manage_registration.php
	|--------------------------------------------------------------------------
	|
	| Script executed when submit button is pressed. Checks if lead visited website before based
	| on cookie file. If file doesn't exists gets current lead information and saves in DB via API.
	| If cookie is saved retrieves lead data from file and updates DB record via API.
	| If cookie is saved but next registration is performed, gets new lead information and saves in DB
	| Moreover executes gis_reg_process.php script. 
	|

	
	|--------------------------------------------------------------------------
	| plugin.php
	|--------------------------------------------------------------------------
	|
	| Main script of plugin. Perfomes some config operations, updates form template 
	| and gets EPXA Leads from API.
	|
	
	
	|--------------------------------------------------------------------------
	| style.css
	|--------------------------------------------------------------------------
	|
	| Basic styles to display form. 
	|
