<?php

/*
Plugin Name: Apture
Plugin URI: http://www.apture.com
Description: A plugin for easily enabling <a href="http://www.apture.com">Apture</a> on Wordpress blogs.
Version: 1.0
Author: Apture, Inc.
Author URI: http://www.apture.com
*/

/*
Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

The name of the author may not be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.   
*/

function apture_options_page () {

        // variables for the field and option names 
        $opt_name = 'apture_site_token';
        $hidden_field_name = 'mt_submit_hidden';
        $data_field_name = 'apture_site_token';

        // Read in existing option value from database
        $opt_val = get_option( $opt_name );

        // See if the user has posted us some information
        // If they did, this hidden field will be set to 'Y'
        if( $_POST[ $hidden_field_name ] == 'Y' ) {
            // Read their posted value
            $opt_val = $_POST[ $data_field_name ];

            // Save the posted value in the database
            update_option( $opt_name, $opt_val );

            // Put an options updated message on the screen

    ?>
    <div class="updated"><p><strong>Options saved.</strong></p></div>
    <?php

        }
        ?>
        <div class="wrap">
        
        <h2>Apture Configuration</h2>
                <div class="narrow">
    <form name="form1" method="post" style="margin: auto; width: 400px; " action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <p>
            Please enter your Site Token to associate this blog with your Apture account. If you don't know your site token <a href="http://www.apture.com/user/apturetokens/" target="_blank">login to Apture</a> to get it. 
            If you don't have an account yet you can create one at <a href="http://www.apture.com/user/register/" target="_blank">Apture.com</a>.
        </p>                                                                   
          

    <h3>Apture Site Token</h3>
    <p style="padding: .5em; background-color: #06c; color: #fff; font-weight: bold;">Please enter your Apture Site Token.
        (<a href="http://www.apture.com/user/apturetokens/" style="color:#fff"  target="_blank">Get your token.</a>)</p>
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

    <p>
    <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
    </p>

    <p class="submit">
    <input type="submit" name="Submit" value="Update Settings" />
    </p>

    </form>
    </div>
    </div>
<?php
    
}

function apture_admin_page () {
    add_options_page('Apture Configuration', 'Apture', 9, basename(__FILE__), 'apture_options_page');
}

function apture_script () {
    echo "<script type='text/javascript' id='aptureScript' src='http://www.apture.com/js/apture.js?siteToken=". get_option('apture_site_token'). "' charset='utf-8'></script>";
}

add_option("apture_site_token", "AxE7oTP");
add_action('admin_menu', 'apture_admin_page');
add_action('wp_footer', 'apture_script');

if ( !get_option('apture_site_token') && !isset($_POST['mt_submit_hidden']) ) {
	function apture_warning() {
		echo "
		<div id=apture-warning' class='updated fade'><p><strong>".__('Apture is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">enter your Apture Site Token</a> for it to work.'), "options-general.php?page=apture.php")."</p></div>
		";
	}
	add_action('admin_notices', 'apture_warning');
	return;
}
?>