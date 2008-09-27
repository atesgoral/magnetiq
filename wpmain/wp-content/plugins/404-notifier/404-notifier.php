<?php

/*
Plugin Name: 404 Notifier
Plugin URI: http://alexking.org/projects/wordpress
Description: This plugin will log 404 hits on your site and can notify you via e-mail or you can subscribe to the generated RSS feed of 404 events. Adjust your settings <a href="options-general.php?page=404-notifier.php">here</a>.
Version: 1.2a
Author: Alex King
Author URI: http://alexking.org
*/ 

// Copyright (c) 2006-2008 Alex King. All rights reserved.
// http://alexking.org/projects/wordpress
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is an add-on for WordPress
// http://wordpress.org/
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

load_plugin_textdomain('404-notifier');

$_SERVER['REQUEST_URI'] = ( isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'] . (( isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')));

class ak_404 {
	var $url_404;
	var $url_refer;
	var $user_agent;
	var $mailto;
	var $mail_enabled;
	var $rss_limit;
	var $options;
	
	function ak_404() {
		if (isset($_SERVER['REQUEST_URI'])) {
			$this->url_404 = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		else {
			$this->url_404 = '';
		}
		if (isset($_SERVER['HTTP_REFERER'])) {
			$this->url_refer = $_SERVER['HTTP_REFERER'];
		}
		else {
			$this->url_refer = '';
		}
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		}
		else {
			$this->user_agent = '';
		}
		$this->mailto = '';
		$this->mail_enabled = 0;
		$this->rss_limit = 100;
		$this->options = array(
			'mailto' => 'email'
			, 'mail_enabled' => 'int'
			, 'rss_limit' => 'int'
		);
	}
	
	function install() {
		global $wpdb;
		$result = $wpdb->query("
			CREATE TABLE `$wpdb->ak_404_log` (
			`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`url_404` TEXT NOT NULL ,
			`url_refer` TEXT NULL ,
			`user_agent` TEXT NULL ,
			`date_gmt` DATETIME NOT NULL
			)
		");
		add_option('ak404_mailto', $this->mailto, 'Address to send mail to.');
		add_option('ak404_mail_enabled', $this->mail_enabled, 'Send mail notifications?');
		add_option('ak404_rss_limit', $this->rss_limit, '# of items to show at once in RSS Feed');
	}
	
	function update_settings() {
		foreach ($this->options as $option => $type) {
			if (isset($_POST[$option])) {
				switch ($type) {
					case 'email':
						$value = stripslashes($_POST[$option]);
						if (!ak_check_email_address($value)) {
							$value = '';
						}
						break;
					case 'int':
						$value = intval($_POST[$option]);
						break;
					default:
						$value = stripslashes($_POST[$option]);
				}
				update_option('ak404_'.$option, $value);
			}
			else {
				update_option('ak404_'.$option, $this->$option);
			}
		}

		header('Location: '.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=404-notifier.php&updated=true');
		die();
	}
	
	function get_settings() {
		foreach ($this->options as $option => $type) {
			$this->$option = get_option('ak404_'.$option);
			switch ($type) {
				case 'email':
					$this->$option = $this->$option;
					break;
				case 'int':
					$this->$option = intval($this->$option);
					break;
			}
		}
	}
	
	function log_404() {
		global $wpdb;
		if (empty($this->url_404)) {
			return;
		}
		$wpdb->query("
			INSERT INTO $wpdb->ak_404_log
			( url_404
			, url_refer
			, user_agent
			, date_gmt
			)
			VALUES
			( '".mysql_real_escape_string($this->url_404)."'
			, '".mysql_real_escape_string($this->url_refer)."'
			, '".mysql_real_escape_string($this->user_agent)."'
			, '".current_time('mysql',1)."'
			)
		");
		$this->mail_404();
	}
	
	function mail_404() {
		if (!empty($this->mailto) && $this->mail_enabled) {
			$to      = $this->mailto;
			$subject = '404: '.$this->url_404;
			$message = '404 Report - a file not found error was registered on your site.'."\n\n"
				.'404 URL:     '.$this->url_404."\n\n"
				.'Referred by: '.$this->url_refer."\n\n"
				.'User Agent: '.$this->user_agent."\n\n";
			$headers = 'From: '.$this->mailto . "\r\n"
				.'Reply-To: '.$this->mailto . "\r\n"
				.'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
		}
	}

	function options_form() {
		switch ($this->mail_enabled) {
			case '1':
				$enabled = ' checked="checked"';
				break;
			case '0':
				$enabled = '';
				break;
		}
		print('
			<div class="wrap">
				<h2>'.__('404 Notifier Options', '404-notifier').'</h2>
				<form name="ak_404" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php" method="post">
					<fieldset class="options">
						<p>
							<input type="checkbox" name="mail_enabled" id="ak404_mail_enabled" value="1" '.$enabled.'/>
							<label for="ak404_mail_enabled">'.__('Enable mail notifications on 404 hits.', '404-notifier').'</label>
						</p>
						<p>
							<label for="mailto">'.__('E-mail address to notify:', '404-notifier').'</label>
							<input type="text" size="35" name="mailto" id="mailto" value="'.htmlspecialchars($this->mailto).'" />
						</p>
						<p>
							<label for="rss_limit">'.__('Limit the RSS Feed to how many items?', '404-notifier').'</label>
							<input type="text" size="5" name="rss_limit" id="rss_limit" value="'.intval($this->rss_limit).'" />
						</p>
						<p><a href="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?ak_action=404_feed">'.__('RSS Feed of 404 Events', '404-notifier').'</a></p>
						<input type="hidden" name="ak_action" value="update_404_settings" />
					</fieldset>
					<p class="submit">
						<input type="submit" name="submit" value="'.__('Update 404 Notifier Settings', '404-notifier').'" />
					</p>
				</form>
			</div>
		');
	}
	
	function rss_feed() {
		global $wpdb;
		$events = $wpdb->get_results("
			SELECT *
			FROM $wpdb->ak_404_log
			ORDER BY date_gmt DESC
			LIMIT $this->rss_limit
		");
		header('Content-type: text/xml; charset=' . get_option('blog_charset'), true);
		echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
>

<channel>
	<title><?php print(__('404 Report for: ', '404-notifier')); bloginfo_rss('name'); ?></title>
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
	<generator>http://wordpress.org/?v=<?php bloginfo_rss('version'); ?></generator>
	<language><?php echo get_option('rss_language'); ?></language>
<?php
		if (count($events) > 0) {
			foreach ($events as $event) {
				$content = '
					<p>'.__('404 URL: ', '404-notifier').'<a href="'.$event->url_404.'">'.$event->url_404.'</a></p>
					<p>'.__('Referring URL: ', '404-notifier').'<a href="'.$event->url_refer.'">'.$event->url_refer.'</a></p>
					<p>'.__('User Agent: ', '404-notifier').$event->user_agent.'</p>
				';
?>
	<item>
		<title><![CDATA[<?php print('404: '.htmlspecialchars($event->url_404)); ?>]]></title>
		<link><![CDATA[<?php print($event->url_404); ?>]]></link>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $event->date_gmt, false); ?></pubDate>
		<guid isPermaLink="false"><?php print($event->id); ?></guid>
		<description><![CDATA[<?php print($content); ?>]]></description>
		<content:encoded><![CDATA[<?php print($content); ?>]]></content:encoded>
	</item>
<?php $items_count++; if (($items_count == get_option('posts_per_rss')) && !is_date()) { break; } } } ?>
</channel>
</rss>
<?php
		die();
	}
}

if (!function_exists('ak_check_email_address')) {
	function ak_check_email_address($email) {
// From: http://www.ilovejackdaniels.com/php/email-address-validation/
// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
			return false;
		}
// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			 if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
				return false;
			}
		}	
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
					return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}
}

function ak404_log() {
	if (is_404()) {
		global $ak404;
		$ak404->log_404();
	}
}
add_action('shutdown', 'ak404_log');

function ak404_options() {
	if (function_exists('add_options_page')) {
		add_options_page(
			__('404 Notifier Options', '404-notifier')
			, __('404 Notifier', '404-notifier')
			, 10
			, basename(__FILE__)
			, 'ak404_options_form'
		);
	}
}
add_action('admin_menu', 'ak404_options');

function ak404_options_form() {
	global $ak404;
	$ak404->options_form();
}

function ak404_request_handler() {
	$ak404 = new ak_404;
	if (!empty($_POST['ak_action'])) {
		switch($_POST['ak_action']) {
			case 'update_404_settings': 
				$ak404->update_settings();
				break;
		}
	}
	if (!empty($_GET['ak_action'])) {
		switch($_GET['ak_action']) {
			case '404_feed':
				$ak404->rss_feed();
				break;
		}
	}
}
add_action('init', 'ak404_request_handler', 99);

function ak404_init() {
	global $ak404, $wpdb;
	$ak404 = new ak_404;
	$wpdb->ak_404_log = $wpdb->prefix.'ak_404_log';
	// CHECK FOR 404 NOTIFIER TABLES
	if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
		$result = mysql_list_tables(DB_NAME);
		$tables = array();
		while ($row = mysql_fetch_row($result)) {
			$tables[] = $row[0];
		}
		if (!in_array($wpdb->ak_404_log, $tables)) {
			$ak404->install();
		}
	}
	$ak404->get_settings();
}
add_action('init', 'ak404_init');

function ak404_admin_head() {
	global $wp_version;
	if (isset($wp_version) && version_compare($wp_version, '2.5', '>=')) {
		print('
<style type="text/css">
fieldset.options {
	border: 0;
	padding: 10px;
}
fieldset.options p {
	margin-bottom: 10px;
}
</style>
		');
	}
}
add_action('admin_head', 'ak404_admin_head');

?>