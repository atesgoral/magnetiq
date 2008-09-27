<?php
/*
Template Name: Category Parent
*/
?>

<?php get_header(); ?>

<div id="content" class="narrowcolumn">

<?php
if (have_posts()) :
   while (have_posts()) :
      the_post();
      the_content();
      wp_list_pages('title_li=' . the_title('<h2>', '</h2>', false) . '&child_of=' . $id);
   endwhile;
endif;
?>


<?php //wp_list_pages('title_li=' . the_title('<h2>', '</h2>', false) . '&child_of=$id' ); ?>

</div>	

<?php get_sidebar(); ?>

<?php get_footer(); ?>
