<?php
// ** MySQL settings ** //
define('DB_NAME', 'db23241_magnetiq');    // The name of the database
define('DB_USER', 'db23241');     // Your MySQL username
define('DB_PASSWORD', 'rX26VpK7'); // ...and password
define('DB_HOST', 'internal-db.s23241.gridserver.com');    // 99% chance you won't need to change this value
define('DB_CHARSET', '');
define('DB_COLLATE', '');

// Change each KEY to a different unique phrase.  You won't have to remember the phrases later,
// so make them long and complicated.  You can visit http://api.wordpress.org/secret-key/1.1/
// to get keys generated for you, or just make something up.  Each key should have a different phrase.
define('AUTH_KEY', 'hzaliRYc)Itf><~yhGbjONb/i9p)FB4@ux9,h[:|#Z&y\\=7~?3`~f]8#7U{1\"= Y');
define('SECURE_AUTH_KEY', 'Z:zqt^Cs5^kTRc4&MY!;I>w8{00DR~9r04S^:=NU..K`*\"R8s0_7y>(c }m-D|Q:');
define('LOGGED_IN_KEY', '5`^8A~JD^L3C(GW/{n]nx??\'$:u>Bq<$x:%>f \"+@/9:H0cZjTk3{SQS_ +m=#^Z');

// You can have multiple installations in one database if you give each a unique prefix
$table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!

// Change this to localize WordPress.  A corresponding MO file for the
// chosen language must be installed to wp-content/languages.
// For example, install de.mo to wp-content/languages and set WPLANG to 'de'
// to enable German language support.
define ('WPLANG', '');

/* That's all, stop editing! Happy blogging. */

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');
?>
