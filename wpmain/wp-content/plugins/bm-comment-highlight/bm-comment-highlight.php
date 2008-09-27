<?php
/*
Plugin Name: BM Comment Highlight
Plugin URI: http://www.binarymoon.co.uk/projects/bm-comment-highlight/
Description: Generate customised class properties to be used for styling comments
Author: Ben Gillbanks
Version: 1.2
Author URI: http://www.binarymoon.co.uk/
*/ 

$bm_postAuthor = "";
$bm_postAuthorEmail = "";
$bm_altComment = "odd";

function bm_getAuthorDetails( $content ) {

	// ----------------------------------------------------------
	// only do it on single pages
	// index/ archive pages don't have comments so not neccessary
	// ----------------------------------------------------------
	if( is_single() ) {
	
		global $bm_postAuthor, $bm_postAuthorEmail;
		
		// get the post author details for comparisson later
		$bm_postAuthor = get_the_author();
		$bm_postAuthorEmail = get_the_author_email();
		
	}
	
	return $content;

}

function bm_commentHighlight() {

	global $comment;
	global $bm_postAuthor, $bm_postAuthorEmail;
	global $bm_altComment;

	// default return value
	$returnClass = "";

	// post author or post reader?
	if ( strcasecmp( $comment->comment_author, $bm_postAuthor ) == 0 && strcasecmp( $comment->comment_author_email, $bm_postAuthorEmail ) == 0 ) {
	
		$returnClass = "author";
		
	} else {
	
		$returnClass = "reader";
		
	}
	
	// registered user?
	if( isset( $comment->user_id ) ) {
	
		if( $comment->user_id > 0 ) {

			// standard user class
			$returnClass .= " user";
			// extra personal class (custom style per user)
			$returnClass .= " userID_" . $comment->user_id;
		
		}
		
	}
	
	// comment type	
	$returnClass .= " " . $comment->comment_type;
	// update for odd and even comments
	$returnClass .= " " . $bm_altComment;
	
	if( $bm_altComment == "odd" ) { 
		$bm_altComment = "even";
	} else {
		$bm_altComment = "odd";
	}

	return $returnClass;

}

add_filter( 'the_content', 'bm_getAuthorDetails' );

?>
