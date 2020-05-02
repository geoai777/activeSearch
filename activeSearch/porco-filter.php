<?php
/*
Plugin Name: p0rc0 active filter
Plugin URI: https://www.airgeo.org
Description: Plugin allows get data from external API and use online filtering to display required data.
Version: 1.0
Author: p0rc0_r0ss0
Author URI: https://github.com/p0rc0jet
License: HVL1.0
*/

/*
 * USER SECTION OF WORDPRESS
 */

function porco_search(){
    /*
     * Handles GUI operations:
     * Generate all html that will be placed at shortcut location.
     */
    $css = '
<style>
#frame  { margin: 0; padding: 0; }
#search { width:200px; }
#result { width:calc(100% - 207px); }
.inline { display: inline-block; background-color:gray; }
.right  { text-align: right; }
.vtop   { vertical-align: top; }
.names  { font-size:0.75rem; font-weight:bold; }
</style>
';
    $html = '
<div id="frame">
    <div id="search" class="inline vtop">
        <h3>Search</h3>
        <form>
            <input id="srcInput" type="text" name="activeSearch" />
        </form>
    </div>
    <div id="result" class="inline vtop">
        <div class="inline right">Results for</div>
        <div id="srcString" class="inline"></div>
        <div id="resWindow">No results yet</div>
    </div>
</div>
';
    $js = '
<script type="text/javascript">
const srcInput = document.getElementById("srcInput");

let timeout = null;
/* /!\ IMPORTANT! delay value is used to prevent rapid search requests 
* i.e. we let user 0.5 sec to finish typing before sending request.
*/
let delay = 500;
var updateThisElement = document.getElementById("resWindow");

function buildUserCard(usrObj){
    /* 
    * Business card html template, takes object and insterts data in places.
    */ 
    let template = "<div id=\"personCard\"> \
    <table><tbody> \
    <tr><th colspan=\"3\"><h3>" + usrObj.displayName + "</h3></th></tr> \
    <tr class=\"names\"><td>Name</td><td>Surname</td><td>Job</td></tr> \
    <tr><td>" + usrObj.givenName + "</td><td>" + usrObj.surname + "</td><td>" + usrObj.jobTitle + "</td></tr> \
    <tr class=\"names\"><td>Mail</td><td>Mobile phone</td><td>Business phones</td></tr> \
    <tr><td>" + usrObj.mail + "</td><td>" + usrObj.mobilePhone + "</td><td>" + usrObj.businessPhones + "</td></tr> \
    <tr class=\"names\"><td>Country</td><td>Office location</td><td>Departament</td></tr> \
    <tr><td>" + usrObj.country + "</td><td>" + usrObj.officeLocation + "</td><td>" + usrObj.departament + "</td></tr> \
    <tr class=\"names\"><td>Principal name</td><td>Manager</td><td>Preferred language</td></tr> \
    <tr><td>" + usrObj.userPrincipalName + "</td><td>" + usrObj.manager + "</td><td>" + usrObj.preferredLanguage + "</td></tr> \
    </tbody></table> \
</div>";
    return template;
}

srcInput.addEventListener("keyup", function(e){
    clearTimeout(timeout);
    timeout = setTimeout(function(){
        ajaxSearch(e.target.value);
    }, delay);
});

function ajaxSearch(srcString){ 
    /*
    * Places xmlhttp request to admin-ajax.php and calls buildUserCard function to
    * render all resulting business cards.
    */ 
    let data = "action=jsSearch&srcString="+srcString;
    let xmlhttp = new XMLHttpRequest();

    xmlhttp.open("POST", "' . admin_url('admin-ajax.php') . '", true);
    xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xmlhttp.send(data);
    xmlhttp.onload = function () {
        if (xmlhttp.status >= 200 && xmlhttp.status < 400) {
            if (typeof updateThisElement !== "undefined" && updateThisElement !== null) {
                let resObj = "";
                if (xmlhttp.responseText.charAt(xmlhttp.responseText.length-1) != "0"){
                    resObj = JSON.parse(xmlhttp.responseText);
                } else {
                    resObj = JSON.parse(xmlhttp.responseText.slice(0,-1));
                }
                let resHTML = "";
                for (let key in resObj){
                    resHTML += buildUserCard(resObj[key]);
                }
                updateThisElement.innerHTML = resHTML;
            }
        } else {
            console.log("There was an error 400");
        }
    }
}

</script>
';
    echo $css . $html . $js;
}

function prSearch(){
    /*
     * API interface function. All data here is provided just to test asynchrous search works
     * Here API calls should be implemented. 
     * $_POST['srcString'] should be used to get/filter API call results.
     */
    if ($_POST['srcString']){
        $db_arr = [
            "id1023" => [
                "displayName" => "Marko",
                "givenName" => "Marko",
                "surname" => "Marino",
                "jobTitle" => "CEO",
                "mail" => "marko@mail.it",
                "mobilePhone" => "+123321123",
                "businessPhones" => "+123321123",
                "officeLocation" => "Milano",
                "preferredLanguage" => "it",
                "userPrincipalName" => "Prin",
                "country" => "Italy",
                "departament" => "Managment",
                "manager" => "Andriano"
            ],
            "id1024" => [
                "displayName" => "Andriano",
                "givenName" => "Adriano",
                "surname" => "Furini",
                "jobTitle" => "CIO",
                "mail" => "adriano@mail.it",
                "mobilePhone" => "+123321123",
                "businessPhones" => "+123321123",
                "officeLocation" => "Milano",
                "preferredLanguage" => "it",
                "userPrincipalName" => "Prin",
                "country" => "Italy",
                "departament" => "Managment",
                "manager" => ""
            ]
        ];
        $db = '
        {"id1023":
            {
              "displayName": "Marko",
              "givenName": "Marko", 
              "surname": "Marino",
              "jobTitle": "CEO",
              "mail": "marko@mail.it",
              "mobilePhone": "+123321123",
              "businessPhones": "+123321123",
              "officeLocation": "Milano",
              "preferredLanguage": "it",
              "userPrincipalName": "Prin",
              "country": "Italy",
              "departament": "Managment",
              "manager": "Andriano"
            },
        "id1024":
            {
              "displayName": "Andriano",
              "givenName": "Adriano", 
              "surname": "Furini",
              "jobTitle": "CIO",
              "mail": "adriano@mail.it",
              "mobilePhone": "+123321123",
              "businessPhones": "+123321123",
              "officeLocation": "Milano",
              "preferredLanguage": "it",
              "userPrincipalName": "Prin",
              "country": "Italy",
              "departament": "Managment",
              "manager": ""
            }
        }';
        echo json_encode($db_arr);
    }
}

add_action('wp_ajax_jsSearch','prSearch');
add_action('wp_ajax_nopriv_jsSearch','prSearch');
add_shortcode('activeSearch', 'porco_search');

/*
 * ADMIN SECTION OF WORDPRESS
 */
function porco_filter_settings(){
    /*
     * Displays settings page in admin interface.
     */
    
    /* Setting defaults */
    add_option ('pf_api-url', '');
    add_option ('pf_api-options', '');
    add_option ('pf_api-format', '');

    /* Check user authorized to work with options */
    if (isset($_POST['pf_send'])) {
        if ( function_exists ('current_user_can') && !current_user_can('manage_options') ) die ( _e('Unauthorized access', 'default'));
        
        if (function_exists('check_admin_referer') ) {
            check_admin_referer('pf_settings_form');
        }
        
        if ($_POST['pf_api-url']){
            $api_url = $_POST['pf_api-url'];
            update_option('pf_api-url', $api_url);
        }
        if ($_POST['pf_api-options']){
            $api_options = $_POST['pf_api-options'];
            update_option('pf_api-options', $api_options);
        }
        if ($_POST['pf_api-format']){
            $api_format = $_POST['pf_api-format'];
            update_option('pf_api-format', $api_format);
        }
    }
     
    $form_html = '<style>'
        . '.inline { display: inline-block; }'
        . '.row { margin: 0.125rem 0 0.125rem 0; }'
        . '.col1 { min-width: 150px; }'
        . '.col2 { }'
        . '.col2 > input { width: 300px; border-radius: 0; }'
        . '.right { text-align: right; }'
        . '</style>'
        . '<h1>Active filter settings</h1>' . "\n"
        . '<form name="pf_settings" method="post" action="'
        . $_SERVER['PHP_SELF'] . '?page=porco-filter&amp;updated=true">' . "\n";
    
    // Early echo, thanks to wp_nonce;
    echo $form_html;

    if (function_exists('wp_nonce_field')) {
        wp_nonce_field('pf_settings_form');
    }
    $form_html = '
<div id="pfSettings">
	<div class="row">
		<div class="col1 inline">API URL:</div>
		<div class="col2 inline">
			<input type="text" class="url" name="pf_api-url" value="'.
			 get_option('pf_api-url')
			.'" />
		</div>
	</div>
	<div class="row">
		<div class="col1 inline">API Options:</div>
		<div class="col2 inline">
			<input type="text" class="string" name="pf_api-options" value="'.
			 get_option('pf_api-options')
			.'" />
		</div>
	</div>	
	<div class="row">
		<div class="col1 inline">API Format:</div>
		<div class="col2 inline">
			<input type="text" class="string" name="pf_api-format" value="'.
			 get_option('pf_api-format')
			.'" />
		</div>
	</div>
	<div class="row">
		<div class="">
			<input type="submit" name="pf_send" value="Submit" class="buttonSave" />
		</div>
	</div>
</div>
</form>';
    echo $form_html;
}

function porco_filter_admin() {
    /**
     * Adds plugin to system, and make accessible via Settings->Porco Filter.
     * Seel add_options_page field description below.
     * title, menu title, user capability, url slug, handler function
     * https://wordpress.org/support/article/roles-and-capabilities/
     */
    add_options_page('Active Filter: Settings', 'Active Filter', 'manage_options', 'porco-filter', 'porco_filter_settings' );
}

add_action('admin_menu', 'porco_filter_admin');

function add_action_links ( $links ) {
    /*
     * Adds "Settings" button to plugins page when plugin is activated 
     */
    $mylinks = array( '<a href="' . admin_url( 'options-general.php?page=porco-filter' ) . '">Settings</a>', );
    return array_merge( $mylinks, $links );
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
?>
