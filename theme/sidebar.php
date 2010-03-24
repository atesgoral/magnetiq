	<div id="sidebar">
		<ul>
		    <li><a href="http://twitter.com/atesgoral" title="Follow me on Twitter"><img src="/i/twit_icon.png" alt="Twitter" border="0"></a></li>
			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h2><?php _e('Author'); ?></h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

			<?php wp_list_pages('title_li=<h2>' . __('Pages') . '</h2>' ); ?>

			<li>
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</li>			

			<li><h2><?php _e('Categories'); ?></h2>
				<ul>
				<?php list_cats(0, '', 'name', 'asc', '', 1, 0, 1, 1, 1, 1, 0,'','','','','') ?>
				</ul>
			</li>


			<li><h2><?php _e('Archives'); ?></h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

				<?php get_links_list(); ?>

		<?php if (function_exists('wp_theme_switcher')) { ?>
			<li><h2><?php _e('Themes'); ?></h2>
			<?php wp_theme_switcher(); ?>
			</li>
		<?php } ?>

		</ul>
	</div>
