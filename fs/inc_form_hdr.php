<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
<title><?php echo $form_title; ?></title>
<link rel="stylesheet" type="text/css" href="fs.css"/>
<script type="text/javascript" src="/js/dtp.js"></script>
<script type="text/javascript">

var g_initialized = false;

function ge(id) { return document.getElementById(id); }

/* Called during initialization and called by nav frame after nav frame is
   initialized.
*/
function checkNav()
{
    if (parent != undefined && parent.nav != undefined &&
        parent.nav.g_initialized && // nav frame must be initialized
        window.updateNav != undefined)
    {
        window.updateNav(parent.nav);
    }
}

function initialize()
{
    checkNav();
    
    if (window.initializeForm != undefined)
    {
        window.initializeForm();
    }
    
    g_initialized = true;
}
</script>
</head>

<body onload="initialize()" class="fixed_w">

<div class="tabs">
    &nbsp;
    <?php echo @$tabs; ?>
</div>

<div class="tabbed_page"></div>
    
<div class="form" id="form">

<h2><?php echo $form_title; ?></h2>
    
<form action="<?php echo $form_action; ?>" method="POST" <?php echo @$form_xtra_attrs; ?>>
