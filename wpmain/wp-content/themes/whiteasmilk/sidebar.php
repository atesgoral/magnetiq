	<div id="sidebar">
		<ul>

			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h2><?php _e('Author'); ?></h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

			<?php wp_list_pages('title_li=<h2>' . __('Pages') . '</h2>' ); ?>

			<li><h2><?php _e('Archives'); ?></h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<li><h2><?php _e('Categories'); ?></h2>
				<ul>
				<?php list_cats(0, '', 'name', 'asc', '', 1, 0, 1, 1, 1, 1, 0,'','','','','') ?>
				</ul>
			</li>

<li>
<?php tla_ads(); ?>
</li>
<!--li>
<script type="text/javascript">< !--
google_ad_client = "pub-2115594125871453";
/* 120x240, created 7/2/08 */
google_ad_slot = "0724765337";
google_ad_width = 120;
google_ad_height = 240;
//-- >
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</li-->

<!--li><p>To support my freeware projects:</p>
<p>
<center>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="business" value="ates@magnetiq.com"><input type="hidden" name="item_name" value="Support for magnetiq.com products"><input type="hidden" name="no_shipping" value="0"><input type="hidden" name="no_note" value="1"><input type="hidden" name="currency_code" value="USD"><input type="hidden" name="tax" value="0"><input type="hidden" name="bn" value="PP-DonationsBF"><input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</center></p>
</li-->
			
				<?php get_links_list(); ?>

		<?php if (function_exists('wp_theme_switcher')) { ?>
			<li><h2><?php _e('Themes'); ?></h2>
			<?php wp_theme_switcher(); ?>
			</li>
		<?php } ?>
			
			
			<li>
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</li>			
<li>
<a class="apture_badge" href="http://www.apture.com"><img src="http://static.apture.com/media/imgs/AptureBadgeBlue.gif" width="88" height="31" border=0 alt="Apture" /></a>
</li>
		</ul>
	</div>
