=== Plugin Name ===
Contributors: BinaryMoon
Tags: comments, highlight, customisation, css, design
Requires at least: 2.0.2
Tested up to: 2.5.1
Stable Tag:1.2

The best comment highlighting plugin around 

== Description ==

I've spent a lot of time searching for a decent comment highlighting plugin but have fond none. Most work fine for a single user but use on a multi author blog and things start to go wrong - so I decided to bite the bullet and develop my own system.

Comment highlight generates css classes that you can add to your comments. These classes will tell you...

1. If the commenter is a registered user or a reader/ commenter
1. If the commenter is the post author
1. The commenters user id (assuming they are a registered member)
1. If the comment is a pingback or trackback

== Installation ==

Upload and activate the plugin as normal.

To use you will need a smattering of html/ php knowledge and some kick ass css-fu. I did consider trying to automate the html side of things but decided that since people format comments differently it would be worth giving everyone the choice to work the way they want too.

Basically all you need to do is use the php command "bm_commentHighlight()" to get the class for the current comment (within the comment loop). You can then print/ echo these in any way you want.

*Example*

&lt;?php foreach ( $bm_comments as $comment ) { 
	<strong>$commentClass = bm_commentHighlight();</strong> ?>
	&lt;li class="&lt;?php <strong>echo $commentClass;</strong> ?>">
	&lt;?php comment_text(); ?>
	&lt;/li>
&lt;?php } ?>

*Classes*

When you call the function you will be returned a string containing a series of classes you can use to style your comments. The classes are as  follows...

- *author* - the commenter is the post author
- *reader* - the commenter is simply a reader of the blog
- *user* - the commenter is a registered user
- *userID_#* - the # is the user id of the registered user that has comented
- *pingback* - the comment is a pingback</li>
- *trackback* - the comment is a trackback</li>
