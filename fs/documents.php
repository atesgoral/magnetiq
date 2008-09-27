<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
    require "inc_db.php";
    
    if (mag_db_connect())
    { 
        $cat_script = mag_db_query_js("SELECT id, name, parent_id FROM category ",
            "createCategory");
        
        $doc_script = mag_db_query_js(
            "SELECT id, title, category_id, alias, summary " . 
            "FROM document ORDER BY title", "createDocument");
    
        $page_script = mag_db_query_js(
            "SELECT id, title, document_id, page_index FROM page " .
            "ORDER BY page_index",
            "createPage");
            
        mag_db_close();
    }
    else
    {
        $cat_script  = "createCategory(1, \"Software\", 0);";
        $cat_script .= "createCategory(2, \"Art\", 0);";
        $cat_script .= "createCategory(3, \"Freeware\", 1);";
        $cat_script .= "createCategory(4, \"Code Snippets\", 1);";

        $doc_script  = "createDocument(1, \"E-Res-Q\", 3, \"eresq\", \"Rescue your mailbox!\");";
        $doc_script .= "createDocument(2, \"Win-Res-Q\", 3, \"winresq\", \"Show hidden windows!\");";
        $doc_script .= "createDocument(3, \"Find Class Names of JavaScript Objects\", 4, \"js_class_names\", \"Dig dis.\");";
        $doc_script .= "createDocument(4, \"Game of Life\", 4, \"game_of_life\", \"Cool!\");";

        $page_script  = "createPage(1, \"What?\", 1, 1);";
        $page_script .= "createPage(2, \"Why?\", 1, 2);";
        $page_script .= "createPage(3, \"Where?\", 1, 3);";
    }
?>
<html xmlns:dcp="http://www.magnetiq.com/ns/2005/08/dcp"
    xmlns:fs="http://www.magnetiq.com/ns/2005/08/fs">
<head>
<title>Flour Sack - Documents</title>
<link rel="stylesheet" type="text/css" href="fs.css"/>
<script type="text/javascript" src="/js/dcp.js"></script>
<script type="text/javascript" src="fs.js"></script>
<script type="text/javascript">

function initialize()
{
    createCategory(0, "(Root)", "container");
    
<?php echo $cat_script . $doc_script . $page_script; ?>

    relocateOrphans();

    g_initialized = true;

    checkContent();
}

function handlePopupPage(popup_info)
{
    if (!handlePopup(popup_info))
    {   
        return false;
    }

    var fs_node = popup_info.source.parentNode.parentNode.fs_node;
    var anchors = popup_info.popup.getElementsByTagName("a");
    
    setAnchorEnabled(anchors[1], fs_node.page_index > 1);
    setAnchorEnabled(anchors[2],
        fs_node.page_index < fs_node.elem.parentNode.childNodes.length - 1);
        
    return true;
}

</script>
</head>

<body onload="initialize()">

<div class="fixed_header">
    <div class="tabs">
        &nbsp;
        <span>Documents</span>
        <a href="resources.php"><span>Resources</span></a>
    </div>
    
    <div class="tabbed_page"></div>
    <div class="header"><h2>Documents</h2></div>
</div>

<div id="cat_container" class="tree_container">
    <div class="cleared">&nbsp;</div>
    <div class="cleared">&nbsp;</div>
    <div id="branch"><div class="cleared">&nbsp;</div></div>
    <div class="cleared">&nbsp;</div>
</div>

<div id="log" style="clear: left">
</div>

<div class="hidden">
    <div id="orphanage"></div>

    <div class="fs_node" id="cat_template">
        <div class="fs_icon"><a href="#" onclick="return handleNodeCollapse(this)"><img src="i/collapse.gif" alt=""/><img src="i/expand.gif" class="hidden" alt=""/></a></div>
        <div class="fs_cap"><a href="cat_form.php?id=%id%" dcp:popup="popup_cat" target="content" onclick="return handleNodeClick(this)" id="cap">&nbsp;</a></div>
        <div class="fs_branch" id="branch"><div class="cleared">&nbsp;</div></div>
        <div class="cleared">&nbsp;</div>
    </div>

    <div class="fs_node" id="doc_template">
        <div class="fs_icon"><a href="#" onclick="return handleNodeCollapse(this)"><img src="i/doc3.gif" alt=""/><img src="i/doc_open.gif" class="hidden" alt=""/></a></div>
        <div class="fs_cap"><a href="doc_form.php?id=%id%" dcp:popup="popup_doc" target="content" onclick="return handleNodeClick(this)" id="cap">&nbsp;</a></div>
        <div class="fs_branch" id="branch"><div class="cleared">&nbsp;</div></div>
        <div class="cleared">&nbsp;</div>
    </div>

    <div class="fs_node" id="page_template">
        <div class="fs_icon"><img src="i/memo.gif" alt=""/></div>
        <div class="fs_cap"><a href="page_form.php?id=%id%" dcp:popup="popup_page" target="content" onclick="return handleNodeClick(this)" id="cap">&nbsp;</a></div>
        <div class="cleared">&nbsp;</div>
    </div>
</div>

<div id="popup_cat" class="menu" dcp:onpopup="handlePopup">
    <div class="outer">
        <div class="inner">
            <ul dcp:no_click="true">
                <li><a fs:href_template="cat_form.php?parent_id=%id%"
                    fs:allow_on_root="true" target="content">Add Sub-category...</a></li>
                <li><a fs:href_template="doc_form.php?category_id=%id%"
                    fs:allow_on_root="true" target="content">Add Document...</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="node_move_select.php?id=%id%&t=cat" target="content">Move Category...</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="cat_form.php?id=%id%" target="content">Edit Category...</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="node_delete_confirm.php?id=%id%&t=cat" target="content">Delete Category</a></li>
            </ul>
        </div>
    </div>
</div>

<div id="popup_doc" class="menu" dcp:onpopup="handlePopup">
    <div class="outer">
        <div class="inner">
            <ul dcp:no_click="true">
                <li><a fs:href_template="page_form.php?document_id=%id%"
                    fs:allow_on_root="true" target="content">Add Page</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="javascript:toggleMoveable(getDocumentElem(%id%))">Move Document...</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="doc_form.php?id=%id%" target="content">Edit Document</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="node_delete_confirm.php?id=%id%&t=doc" target="content">Delete Document</a></li>
            </ul>
        </div>
    </div>
</div>

<div id="popup_page" class="menu" dcp:onpopup="handlePopupPage">
    <div class="outer">
        <div class="inner">
            <ul dcp:no_click="true">
                <li><a fs:href_template="javascript:toggleMoveable(getPageElem(%id%))">Move Page...</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="page_swap.php?id=%id%&v=%2D1" target="content">Bring Page Forward</a></li>
                <li><a fs:href_template="page_swap.php?id=%id%&v=1" target="content">Send Page Backward</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="page_form.php?id=%id%" target="content">Edit Page</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="node_delete_confirm.php?id=%id%&t=page" target="content">Delete Page</a></li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
