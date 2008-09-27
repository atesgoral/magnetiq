<?php

/*
Plugin Name: Text Link Ads
Plugin URI: http://www.text-link-ads.com/
Description: Really Simple Web/RSS Advertising For Personal or Commercial Use
Author: Text Link Ads
Version: 3.1.0
Author URI: http://www.text-link-ads.com/
*/

if(!function_exists('add_action')){
	header('HTTP/1.0 404 Not Found');
	header('Location: ../../');
	exit();
}


$text_link_ads_object = null;

// general/syncing hooks
add_action('init', 			'tla_initialize');
add_action('plugins_loaded','advertisement_widget_init');
add_action('shutdown', 		'tla_shutdown');
add_action('publish_post', 	'tla_process_new_post');
add_action('publish_page', 	'tla_process_new_post');
add_action('edit_post', 	'tla_send_updated_post_alert');
add_action('delete_post', 	'tla_send_deleted_post_alert');
add_action('activate_tla_240379.php', 'tla_check_installation');

// rss hooks (only exist in 2.0+)
add_action('rss_head', 		'tla_initialize_rss_rss1');
add_action('rss2_head', 	'tla_initialize_rss_default');
add_action('rdf_header', 	'tla_initialize_rss_default');
add_action('atom_head', 	'tla_initialize_rss_default');





function tla_initialize()
{
	global $wpdb, $text_link_ads_object;
	$text_link_ads_object = new TextLinkAdsObject;
	$text_link_ads_object->initialize();

	if($_REQUEST['textlinkads_key'] == $text_link_ads_object->websiteKey){
	
		switch($_REQUEST['textlinkads_action'])
		{
	
			case 'debug':
				$text_link_ads_object->debug($_REQUEST['textlinkads_reset_index']);
				exit;
	
			case 'sync_posts':
				if(isset($_REQUEST['textlinkads_post_id']) && !empty($_REQUEST['textlinkads_post_id']))
					$text_link_ads_object->outputPostForSyncing($_REQUEST['textlinkads_post_id']);
				else
					$text_link_ads_object->initialPostSync();
				exit;
	
			case 'reset_syncing':
				update_option($text_link_ads_object->lastSyncIdOption, '0');
				break;
	
			case 'reset_sync_limit':
				$maxId = $wpdb->get_var("SELECT ID FROM $wpdb->posts ORDER BY ID DESC LIMIT 1");
				if($maxId === '') $maxId = '0';
				update_option($text_link_ads_object->maxSyncIdOption, $maxId);
				break;
		}
		
	}

	add_filter('the_content', 'tla_concat_post_ad', 1);
}


function tla_check_installation()
{
	global $text_link_ads_object;

	$text_link_ads_object = new TextLinkAdsObject;
	$text_link_ads_object->checkInstallation();
}


function advertisement_widget_init()
{
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ) return;
	
	register_sidebar_widget('Advertisement', 'advertisement_widget');
	register_widget_control('Advertisement', 'advertisement_widget_control');
}
 

function advertisement_widget($args)
{
	extract($args);

	$options = get_option('widget_advertisement');
	$title = $options['title'];

	echo $before_widget;
	echo $before_title . $title . $after_title;

	tla_ads();

	echo $after_widget;
}


function advertisement_widget_control()
{
	$options = $newoptions = get_option('widget_advertisement');

	if ( $_POST['advertisement-title'] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST['advertisement-title']));
	}

	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_advertisement', $options);
	}

	?>
			<p><label for="advertisement-title">Title: <input type="text" style="width: 250px;" id="advertisement-title" name="advertisement-title" value="<?php echo htmlspecialchars($options['title']); ?>" /></label></p>
			<input type="hidden" name="advertisement-submit" id="advertisement-submit" value="1" />
<?php
}


function tla_shutdown()
{
	global $text_link_ads_object;

	if(is_object($text_link_ads_object)) $text_link_ads_object->destruct();
}


function tla_initialize_rss_default()
{
	global $text_link_ads_object;

	$text_link_ads_object->initializeRss();
	$text_link_ads_object->setRssExcerptOption(0);
	add_filter('the_content', 'tla_concat_rss_ad', 1);
}


function tla_initialize_rss_rss1()
{
	global $text_link_ads_object;

	$text_link_ads_object->initializeRss();
	$text_link_ads_object->setRssExcerptOption(1);
	add_filter('the_excerpt_rss', 'tla_concat_rss_ad', 1);
}


function tla_ads()
{
	global $text_link_ads_object;

	$text_link_ads_object->outputHtmlAds();
}


function tla_concat_post_ad($content = '')
{
	global $text_link_ads_object, $wpdb, $post;

	if(is_object($post) && is_object($text_link_ads_object)) $content .= $text_link_ads_object->returnPostAd($post->ID);

	return $content;
}


function tla_concat_rss_ad($content = '')
{
	global $text_link_ads_object, $post;

	if(is_object($text_link_ads_object) && is_object($post)){
		$content .= $text_link_ads_object->returnRssAd($post->ID, $post->post_date);
	}

	return $content;
}


function tla_process_new_post($postId)
{
	global $text_link_ads_object;

	$text_link_ads_object->initializeRss();
	$text_link_ads_object->returnRssAd($postId, date('Y-m-d H:i:s'));

	tla_send_new_post_alert($postId);
}


function tla_send_new_post_alert($postId)
{
	global $text_link_ads_object;

	$text_link_ads_object->postLevelPing($text_link_ads_object->tlaPingUrl.'?action=add&inventory_key='.$text_link_ads_object->websiteKey.'&post_id='.$postId, 80);
}


function tla_send_updated_post_alert($postId)
{
	global $text_link_ads_object;

	$text_link_ads_object->postLevelPing($text_link_ads_object->tlaPingUrl.'?action=update&inventory_key='.$text_link_ads_object->websiteKey.'&post_id='.$postId, 80);
}


function tla_send_deleted_post_alert($postId)
{
	global $text_link_ads_object;

	$text_link_ads_object->postLevelPing($text_link_ads_object->tlaPingUrl.'?action=delete&inventory_key='.$text_link_ads_object->websiteKey.'&post_id='.$postId, 80);
}





class TextLinkAdsObject
{
	var $websiteKey			= '6H49I9XMC6L8RMSB5LE7';
	var $websiteId			= 240379;

	// we do not recommend changing these values
	var $tlaPingUrl			= 'http://www.text-link-ads.com/post_level_sync.php';
	var $xmlRefreshTime 	= 900;
	var $connectionTimeout 	= 10;
	var $tlaDataTable		= 'tla_data';
	var $rssMapTable		= 'tla_rss_map';

	var $lastUpdateOption	= 'tla_last_update';
	var $rssInstalledOption	= 'tla_rss_installed';
	var $rssMaxAdsOption	= 'tla_rss_max_ads';
	var $rssIndexOption		= 'tla_rss_index';
	var $lastSyncIdOption	= 'tla_last_sync_post_id';
	var $maxSyncIdOption	= 'tla_max_sync_post_id';

	var $ads;
	var $postAds;
	var $rssActiveAds;
	var $rssStoredAds;
	var $rssMaxAds;
	var $rssCurrentIndex;
	var $rssInstalled;
	var $rssExcerptSetting;
	var $rssRestoreSettings = false;


	function TextLinkAdsObject()
	{
		global $table_prefix;

		$this->tlaDataTable = $table_prefix.$this->tlaDataTable;
		$this->rssMapTable  = $table_prefix.$this->rssMapTable;

	}


	function debug($resetIndex = false)
	{
		global $wpdb;

		if($resetIndex){
			add_option($this->rssIndexOption, '1', 'Stores the index for next rss ad to display.');
			update_option($this->rssIndexOption, '1');
			$this->rssCurrentIndex = 1;
		}

		echo "<b>Last Refresh:</b> "		.get_option($this->lastUpdateOption)	."<br>\n";
		echo "<b>RSS Installed:</b> "		.get_option($this->rssInstalledOption)	."<br>\n";
		echo "<b>RSS Max Ads:</b> "			.get_option($this->rssMaxAdsOption)		."<br>\n";
		echo "<b>RSS Current Index:</b> "	.get_option($this->rssIndexOption)		."<br><br>\n";

		if($wpdb->get_var("SHOW TABLES LIKE '$this->tlaDataTable'") != $this->tlaDataTable) {
			echo "Text Link Ads data table is <b>not installed</b> (".$this->tlaDataTable.")<br><br>\n";
		}else{
			echo "Text Link Ads data table is installed (".$this->tlaDataTable.")<br><br>\n";
			print_r($wpdb->get_results("SELECT * FROM `$this->tlaDataTable`"));
			echo "<br><br>";
		}

		if($wpdb->get_var("SHOW TABLES LIKE '$this->rssMapTable'") != $this->rssMapTable) {
			echo "Text Link Ads RSS map table is <b>not installed</b> (".$this->rssMapTable.")<br><br>\n";
		}else{
			echo "Text Link Ads RSS map table is installed (".$this->rssMapTable.")<br><br>\n";
			print_r($wpdb->get_results("SELECT * FROM `$this->rssMapTable` ORDER BY post_id DESC LIMIT ".get_option('posts_per_rss')));
			echo "<br><br>";
		}

	}


	function installDatabase()
	{
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		$sql = "CREATE TABLE `$this->tlaDataTable` (
				  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				  `post_id` bigint(20) unsigned NOT NULL default '0',
				  `url` TEXT NOT NULL,
				  `text` TEXT NOT NULL,
				  `before_text` TEXT NOT NULL,
				  `after_text` TEXT NOT NULL,
				  `rss_text` TEXT NOT NULL,
				  `rss_before_text` TEXT NOT NULL,
				  `rss_after_text` TEXT NOT NULL,
				  `rss_prefix` VARCHAR(255) NOT NULL DEFAULT '',
				  PRIMARY KEY  (`id`),
				  KEY `post_id` (`post_id`)
				) TYPE=MyISAM AUTO_INCREMENT=1 ;";

		dbDelta($sql);

		$sql = "CREATE TABLE `$this->rssMapTable` (
					`post_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
					`advertisement` TEXT NOT NULL ,
					PRIMARY KEY ( `post_id` )
				) TYPE = MYISAM ;";

		dbDelta($sql);

		add_option($this->rssInstalledOption, date('Y-m-d H:i:s'), 'Stores the date that Text Link Ads rss was installed.');
		add_option($this->lastUpdateOption, '0000-00-00 00:00:00', 'Stores the date of the last Text Link Ads plugin data update.');
		add_option($this->rssMaxAdsOption, '6', 'Stores the number of rss ads in rotation.');
		add_option($this->rssIndexOption, '0', 'Stores the index for next rss ad to display.');
		
		if( get_option($this->maxSyncIdOption) > 0 ) return;

		$maxId = $wpdb->get_var("SELECT ID FROM $wpdb->posts ORDER BY ID DESC LIMIT 1");
		if($maxId === '') $maxId = '0';

		add_option($this->lastSyncIdOption, '0', 'The ID of the last post synced with Text Link Ads');
		add_option($this->maxSyncIdOption, $maxId, 'The highest post ID to be batch synced with Text Link Ads');

		$this->postLevelPing($this->tlaPingUrl.'?action=install&inventory_key='.$this->websiteKey.'&site_url='.urlencode(get_option('siteurl')), 80);
	}


	function installRss()
	{
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		$sql = "CREATE TABLE `$this->rssMapTable` (
					`post_id` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
					`advertisement` TEXT NOT NULL ,
					PRIMARY KEY ( `post_id` )
				) TYPE = MYISAM ;";

		dbDelta($sql);

		add_option($this->rssInstalledOption, date('Y-m-d H:i:s'), 'Stores the date that Text Link Ads rss was installed.');
		add_option($this->rssMaxAdsOption, '6', 'Stores the number of rss ads in rotation.');
		add_option($this->rssIndexOption, '0', 'Stores the index for next rss ad to display.');
	}


	function installPostLevel()
	{
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

		$wpdb->query("ALTER TABLE `$this->tlaDataTable` ADD `post_id` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `id` ;");

		$wpdb->query("ALTER TABLE `$this->tlaDataTable` ADD INDEX ( `post_id` ) ;");
		
		if( get_option($this->maxSyncIdOption) > 0 ) return;

		$maxId = $wpdb->get_var("SELECT ID FROM $wpdb->posts ORDER BY ID DESC LIMIT 1");
		if($maxId === '') $maxId = '0';

		add_option($this->lastSyncIdOption, '0', 'The ID of the last post synced with Text Link Ads');
		add_option($this->maxSyncIdOption, $maxId, 'The highest post ID to be batch synced with Text Link Ads');

		$this->postLevelPing($this->tlaPingUrl.'?action=install&inventory_key='.$this->websiteKey.'&site_url='.urlencode(get_option('siteurl')), 80);
	}


	function checkInstallation()
	{
		global $wpdb;

		if($wpdb->get_var("SHOW TABLES LIKE '$this->tlaDataTable'") != $this->tlaDataTable) {
			$this->installDatabase();
		}else if($wpdb->get_var("SHOW TABLES LIKE '$this->rssMapTable'") != $this->rssMapTable){
			$this->installRss();
		}else if($wpdb->get_var("SHOW COLUMNS FROM $this->tlaDataTable LIKE 'post_id'") != 'post_id'){
			$this->installPostLevel();
		}
	}


	function initialize()
	{
		global $wpdb;
		
		$this->checkInstallation();

		$rssFiles = array('wp-atom.php', 'wp-rdf.php', 'wp-rss.php', 'wp-rss2.php');
		$scriptName = strtolower(basename($_SERVER['SCRIPT_FILENAME']));
		$rss = in_array($scriptName, $rssFiles) ? true : false;

		if(	get_option($this->lastUpdateOption) < date('Y-m-d H:i:s', time() - $this->xmlRefreshTime) ||
			get_option($this->lastUpdateOption) > date('Y-m-d H:i:s') )
		{
			$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
			$userAgent  = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
			$this->updateLocalAds("http://www.text-link-ads.com/xml.php?inventory_key=".$this->websiteKey."&referer=" . urlencode($request_uri) .  "&user_agent=" . urlencode($user_agent) . ($rss ? '&rss_access=true' : ''));
		}

		$this->ads = array();
		$this->postAds = array();

		$ads = $wpdb->get_results("SELECT * FROM $this->tlaDataTable");

		if(!is_array($ads)) return;

		foreach($ads as $ad){
			if($ad->post_id > 0)
				$this->postAds[$ad->post_id] = $ad;
			else
				$this->ads[] = $ad;
		}
	}


	function initializeRss()
	{
		$this->rssInstalled 	= get_option($this->rssInstalledOption);
		$this->rssMaxAds 		= get_option($this->rssMaxAdsOption);
		$this->rssCurrentIndex 	= get_option($this->rssIndexOption);

		if($this->rssCurrentIndex === false){
			add_option($this->rssIndexOption, '0', 'Stores the index for next rss ad to display.');
			$this->rssCurrentIndex = 0;
		}

		$this->loadStoredRssAds();
		$this->loadActiveRssAds();

		remove_filter('the_content', 'tla_concat_post_ad');
	}


	function destruct()
	{
		if($this->rssRestoreSettings){
			update_option('rss_use_excerpt', $this->rssExcerptSetting);
		}
	}


	function setRssExcerptOption($val = 0)
	{
		$this->rssRestoreSettings = true;
		$this->rssExcerptSetting = get_option('rss_use_excerpt');
		update_option('rss_use_excerpt', $val);
	}


	function updateLocalAds($url)
	{
		global $wpdb;

		update_option($this->lastUpdateOption, date('Y-m-d H:i:s'));

		if($xml = $this->fetchLiveXml($url)) {

			$xmlData = $this->decodeXml($xml);

			$wpdb->query("TRUNCATE `$this->tlaDataTable`");

			if( is_array($xmlData['URL']) ){

				$query = "INSERT INTO $this->tlaDataTable ( `url`, `post_id`, `text`, `before_text`, `after_text`, `rss_text`, `rss_before_text`, `rss_after_text`, `rss_prefix`) VALUES ";
				for ($i = 0; $i < count($xmlData['URL']); $i++) {
					$query .= " (
						'".mysql_real_escape_string($xmlData['URL'][$i])."',
						'".mysql_real_escape_string( isset($xmlData['PostID'][$i]) ? $xmlData['PostID'][$i] : 0 )."',
						'".mysql_real_escape_string(trim($xmlData['Text'][$i]))."',
						'".mysql_real_escape_string(trim($xmlData['BeforeText'][$i]))."',
						'".mysql_real_escape_string(trim($xmlData['AfterText'][$i]))."',
						'".mysql_real_escape_string($xmlData['RssText'][$i])."',
						'".mysql_real_escape_string($xmlData['RssBeforeText'][$i])."',
						'".mysql_real_escape_string($xmlData['RssAfterText'][$i])."',
						'".mysql_real_escape_string($xmlData['RssPrefix'][$i])."'
					),";

				}
				$this->rssMaxAds = $xmlData['RssMaxAds'][0];

				$query = substr($query, 0, strlen($query)-1);
				$wpdb->query($query);

				update_option($this->rssMaxAdsOption, $this->rssMaxAds);
			}
		}
	}


	function postLevelPing($url)
	{
		$url = parse_url($url);

		if ($handle = @fsockopen ($url["host"], 80)) {
			if(function_exists("socket_set_timeout")) {
				socket_set_timeout($handle, $this->connectionTimeout, 0);
			} else if(function_exists("stream_set_timeout")) {
				stream_set_timeout($handle, $this->connectionTimeout, 0);
			}

			fwrite ($handle, "GET $url[path]?$url[query] HTTP/1.0\r\nHost: $url[host]\r\nConnection: Close\r\n\r\n");
			fclose($handle);

			return true;
		}

		return false;
	}


	function fetchLiveXml($url)
	{
		$result = '';
		$url = parse_url($url);

		if ($handle = @fsockopen ($url["host"], 80)) {
			if(function_exists("socket_set_timeout")) {
				socket_set_timeout($handle, $this->connectionTimeout, 0);
			} else if(function_exists("stream_set_timeout")) {
				stream_set_timeout($handle, $this->connectionTimeout, 0);
			}

			fwrite ($handle, "GET $url[path]?$url[query] HTTP/1.0\r\nHost: $url[host]\r\nConnection: Close\r\n\r\n");
			while (!feof($handle)) {
				$result .= @fread($handle, 40960);
			}
			fclose($handle);

			$result = substr($result, strpos($result,'<?'));
		}

		return $result;
	}


	function decodeXml($xml)
	{

		if( !function_exists('html_entity_decode') ){
			function html_entity_decode($string)
			{
			   // replace numeric entities
			   $str = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\1"))', $str);
			   $str = preg_replace('~&#([0-9]+);~e', 'chr(\1)', $str);
			   // replace literal entities
			   $transTable = get_html_translation_table(HTML_ENTITIES);
			   $transTable = array_flip($transTable);
			   return strtr($str, $transTable);
			}
		}

		$out		= array();
		$returnData = array();

		preg_match_all ("/<(.*?)>(.*?)</", $xml, $out, PREG_SET_ORDER);
		$search  = array('&#60;', '&#62;', '&#34;');
		$replace = array('<', '>', '"');
		$n = 0;
		while (isset($out[$n]))
		{
			$returnData[$out[$n][1]][] = str_replace($search, $replace, html_entity_decode(strip_tags($out[$n][0])));
			$n++;
		}
		return $returnData;
	}


	function outputHtmlAds()
	{
		foreach($this->ads as $key => $ad) {
			if(trim($ad->text) == '' && trim($ad->before_text) == '' && trim($ad->after_text) == '') unset($this->ads[$key]);
		}

		if( count($this->ads) > 0 ){
			echo "\n<ul>\n";
			foreach($this->ads as $ads) {
				echo "<li>".$ads->before_text." <a href=\"".$ads->url."\">".$ads->text."</a> ".$ads->after_text."</li>\n";
			}
			echo "</ul>";
		}
	}


	function loadStoredRssAds()
	{
		global $wpdb;

		$this->rssStoredAds = array();

		$rssAds = $wpdb->get_results("SELECT * FROM `$this->rssMapTable` ORDER BY post_id DESC LIMIT ".get_option('posts_per_rss'));

		if(is_array($rssAds)) foreach($rssAds as $ad) $this->rssStoredAds[$ad->post_id] = $ad->advertisement;
	}


	function loadActiveRssAds()
	{
		$this->rssActiveAds = array();

		if(is_array($this->ads))
		foreach($this->ads as $ad) {
			if ($ad->rss_prefix == ''){
				continue;
			}else if(trim($ad->rss_text) == '' && trim($ad->rss_before_text) == '' && trim($ad->rss_after_text) == ''){
				$this->rssActiveAds[] = "";
			}else{
				$this->rssActiveAds[] = "<strong><em>$ad->rss_prefix</em></strong>: $ad->rss_before_text <a href=\"$ad->url\">$ad->rss_text</a><em> </em>$ad->rss_after_text<br />";
			}
		}
	}


	function returnRssAd($postId, $postDate)
	{
		global $wpdb;

		if($postDate < $this->rssInstalled) return;

		$returnAd = '';

		if( isset($this->rssStoredAds[$postId]) ){
			$returnAd = $this->rssStoredAds[$postId];
		}else if($this->rssMaxAds > 0 && trim($this->rssMaxAds) != ''){
			$this->rssCurrentIndex = $this->rssCurrentIndex % $this->rssMaxAds;

			$returnAd = isset($this->rssActiveAds[$this->rssCurrentIndex]) ? $this->rssActiveAds[$this->rssCurrentIndex] : '';

			$postId = (int) $postId;
			
			$result = $wpdb->query("INSERT IGNORE INTO `$this->rssMapTable` SET post_id = '".mysql_real_escape_string($postId)."', advertisement = '".mysql_real_escape_string($returnAd)."'");

			if($result === 1){
				$this->rssStoredAds[$postId] = $returnAd;

				$this->rssCurrentIndex++;
				$this->rssCurrentIndex = $this->rssCurrentIndex % $this->rssMaxAds;
			}

			update_option($this->rssIndexOption, $this->rssCurrentIndex);
		}

		return ($returnAd != '' ? "<p>$returnAd</p>" : '');
	}


	function returnPostAd($postId)
	{	
		if(isset($this->postAds[$postId])){
			$ad = $this->postAds[$postId];
			return "\n\n$ad->before_text <a href=\"$ad->url\">$ad->text</a> $ad->after_text";
		}

		return '';
	}


	function outputPostForSyncing($postId)
	{
		global $wpdb;
		
		$postId = (int) $postId;

		$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID = '".mysql_real_escape_string($postId)."'");

		$this->outputPostsForSyncing($posts);
	}


	function outputPostsForSyncing($posts)
	{
		echo "<?xml version=\"1.0\" ?>\n<posts>\n";
		if(is_array($posts)){

			foreach($posts as $post){
				echo "<post>\n"
						. "<id>".$post->ID."</id>\n"
						. "<title>".urlencode($post->post_title)."</title>\n"
						. "<date>".$post->post_date_gmt."</date>\n"
						. "<url>".get_permalink($post->ID)."</url>\n"
					. "</post>\n";
			}

		}
		echo "</posts>\n";
		exit();
	}


	function initialPostSync()
	{
		global $wpdb;

		$lastId = get_option($this->lastSyncIdOption);
		$maxId = get_option($this->maxSyncIdOption);

		if($lastId === '' || $lastId === false) $lastId = 0;
		if($maxId === '' || $maxId === false)  $maxId  = 999999;
		
		$lastId = (int) $lastId;
		$maxId  = (int) $maxId;

		$query 	 = "SELECT * FROM $wpdb->posts
					WHERE (post_status = 'publish' OR post_status = 'static') AND ID > '$lastId' AND ID <= '$maxId'
					ORDER BY ID ASC LIMIT 100";


		$posts = $wpdb->get_results($query);

		if(is_array($posts) && count($posts) > 0){
			$lastIndex = count($posts) - 1;
			$lastId = $posts[$lastIndex]->ID;
			update_option($this->lastSyncIdOption, $lastId);
		}

		$this->outputPostsForSyncing($posts);
	}


}

?>
