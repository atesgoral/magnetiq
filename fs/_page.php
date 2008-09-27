<?xml version="1.0"?>
<?php
    require "fs/inc_db.php";
    
    $docid = @$_GET["docid"];
    //$alias = @$_GET["a"];
    $page_index = @$_GET["p"];
    
    if (!isset($page_index))
    {
        $page_index = 1;
    }
    
    if (!mag_db_connect())
        die("Cannot connect to database");

    list($page_title, $body) = mag_db_query_row("SELECT title, body FROM page WHERE document_id = $docid AND page_index = $page_index");
    list($doc_title, $alias, $category_id) = mag_db_query_row("SELECT title, alias, category_id FROM document WHERE id = $docid");
    mag_db_query_col("SELECT COUNT(*) FROM page WHERE document_id = $docid", 0, $page_count);
    
    $doc_base = "page.php?docid=$docid&amp;a=$alias";
    
    $parent_id = $category_id;
    
    $path_str = $page_index > 1 ?
        "<a href=\"$doc_base\">$doc_title</a>" :
        $doc_title;

    do
    {
        list($category_name, $parent_id) = mag_db_query_row("SELECT name, parent_id FROM category WHERE id = $parent_id");
        $path_str = "<a href=\"cat_get.php?id=$parent_id\">$category_name</a> &raquo; $path_str"; // parnet_id!!!
    }
    while ($parent_id > 0);
    
    //$path_str = "<a href=\".\">Home</a> &raquo; $path_str &raquo; $page_title";
    $path_str = "<a href=\".\">Home</a> &raquo; $path_str";

    if ($page_index > 1)
    {
        $page_str = "<a href=\"$doc_base&amp;p=" . ($page_index - 1) . "\">&lt;</a> ";
    }
    else
    {
        $page_str = "";
    }
    
    $page_str .= "Page $page_index of $page_count";

    if ($page_index < $page_count)
    {
        $page_str .= " <a href=\"$doc_base&amp;p=" . ($page_index + 1) . "\">&gt;</a>";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

<head>
<title><?php echo $doc_title; ?></title>
<link rel="stylesheet" type="text/css" href="/20050904.css"/>
<link rel="stylesheet" type="text/css" href="/synh_20050908.css"/>
<script type="text/javascript" src="/js/synh.js"></script>
<script type="text/javascript" src="/20050908.js"></script>
<script type="text/javascript">
function initialize()
{
    syntaxHighlightAll(true);
}
</script>
</head>

<body onload="initialize()">
    <div class="nav">
        <div class="breadcrumbs"><?php echo $path_str; ?></div>
        <div class="paging"><?php echo $page_str; ?></div>
        <div style="clear: left;"></div>
    </div>
    <div id="options">
        <input type="checkbox" onclick="syntaxHighlightAll(this.checked)" checked="checked"/> Syntax Highlighting
    </div>    
    <div id="content">
        <h3><?php echo $page_title; ?></h3>
        <?php echo $body; ?>
    </div>
    <div class="nav">
        <div class="breadcrumbs"><?php echo $path_str; ?></div>
        <div class="paging"><?php echo $page_str; ?></div>
    </div>
</body>

</html>
