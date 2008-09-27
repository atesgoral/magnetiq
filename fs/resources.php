<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
    require "inc_db.php";
    
    if (mag_db_connect())
    {
        $fld_script = mag_db_query_js("SELECT id, name, parent_id FROM folder ",
             "createFolder");
        
        $rsc_script = mag_db_query_js(
            "SELECT id, name, folder_id FROM resource ORDER BY name",
            "createResource");
            
        mag_db_close();
    }
    else
    {
        $fld_script  = "createFolder(1, \"MP3\", 0);";
        $fld_script .= "createFolder(2, \"Images\", 0);";
        $fld_script .= "createFolder(3, \"Rock\", 1);";
        $fld_script .= "createFolder(4, \"Pop\", 1);";

        $rsc_script  = "createResource(1, \"One.mp3\", 3);";
        $rsc_script .= "createResource(2, \"Two.mp3\", 3);";
        $rsc_script .= "createResource(3, \"Rock.mp3\", 4);";
        $rsc_script .= "createResource(4, \"Roll.mp3\", 4);";
    }
    
    //getAttributeNS not working?
    //setting className doesn't work?
?>
<html xmlns:dcp="http://www.magnetiq.com/ns/2005/08/dcp"
    xmlns:fs="http://www.magnetiq.com/ns/2005/08/fs">
<head>
<title>Flour Sack - Resources</title>
<link rel="stylesheet" type="text/css" href="fs.css"/>
<script type="text/javascript" src="/js/dcp.js"></script>
<script type="text/javascript" src="fs.js"></script>
<script type="text/javascript">

function initialize()
{
    createFolder(0, "(Root)", "container");

<?php echo $fld_script . $rsc_script; ?>

    relocateOrphans();

    g_initialized = true;

    checkContent();
}

function handlePopupRsc(popup_info)
{
    if (!handlePopup(popup_info))
    {   
        return false;
    }

    var anchors = popup_info.popup.getElementsByTagName("a");
    
    var content = getContent();
    
    var enable_embed = content != undefined && content.embedStr != undefined;

    setAnchorEnabled(anchors[0], enable_embed);
    setAnchorEnabled(anchors[1], enable_embed);
    setAnchorEnabled(anchors[2], enable_embed);
        
    return true;
}

function embedRsc(id, type)
{
    var url = "rsc_get.php?id=" + id;
    var str;
    
    switch (type)
    {
    case "img":
        str = "<img src=\"" + url + "\"/>";
        break;

    case "link":
        str = "<a href=\"" + url + "\"></a>";
        break;

    default:        
        str = url;
    }

    var content = getContent();
    
    if (content != undefined && content.embedStr != undefined)
    {
        content.embedStr(str);
    }
/*

    insertAtCaret(this.form.aTextArea, 
     this.form.aText.value)
             ONSELECT="storeCaret(this);"
               ONCLICK="storeCaret(this);"
               ONKEYUP="storeCaret(this);"
               
    function storeCaret (textEl) {
       if (textEl.createTextRange) 
         textEl.caretPos = document.selection.createRange().duplicate();
     }
          
      if (textEl.createTextRange && textEl.caretPos) {
         var caretPos = textEl.caretPos;
         caretPos.text =
           caretPos.text.charAt(caretPos.text.length - 1) == ' ' ?
             text + ' ' : text;
       }
       else
         textEl.value  = text;
     }     
*/
}

</script>
</head>

<body onload="initialize()">

<div class="fixed_header">
    <div class="tabs">
        &nbsp;
        <a href="documents.php"><span>Documents</span></a>
        <span>Resources</span>
    </div>
    
    <div class="tabbed_page"></div>
    <div class="header"><h2>Resources</h2></div>
</div>

<div id="fld_container" class="tree_container">
    <div class="cleared">&nbsp;</div>
    <div class="cleared">&nbsp;</div>
    <div id="branch"><div class="cleared">&nbsp;</div></div>
    <div class="cleared">&nbsp;</div>
</div>

<div id="log" style="clear: left">
</div>

<div class="hidden">
    <div id="orphanage"></div>

    <div class="fs_node" id="fld_template">
        <div class="fs_icon"><a href="#" onclick="return handleNodeCollapse(this)"><img src="i/collapse.gif" alt=""/><img src="i/expand.gif" class="hidden" alt=""/></a></div>
        <div class="fs_cap"><a href="fld_form.php?id=%id%" dcp:popup="popup_fld" target="content" onclick="return handleNodeClick(this)" id="cap">&nbsp;</a></div>
        <div class="fs_branch" id="branch"><div class="cleared">&nbsp;</div></div>
        <div class="cleared">&nbsp;</div>
    </div>

    <div class="fs_node" id="rsc_template">
        <div class="fs_icon"><img src="i/memo.gif" alt=""/></div>
        <div class="fs_cap"><a href="rsc_form.php?id=%id%" dcp:popup="popup_rsc" target="content" onclick="return handleNodeClick(this)" id="cap">&nbsp;</a></div>
        <div class="cleared">&nbsp;</div>
    </div>
</div>

<div id="popup_fld" class="menu" dcp:onpopup="handlePopup">
    <div class="outer">
        <div class="inner">
            <ul dcp:no_click="true">
                <li><a fs:href_template="fld_form.php?parent_id=%id%"
                    fs:allow_on_root="true" target="content">Add Sub-folder</a></li>
                <li><a fs:href_template="rsc_form.php?folder_id=%id%"
                    fs:allow_on_root="true" target="content">Add Resource</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="javascript:toggleMoveable(getFolderElem(%id%))">Move Folder</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="fld_form.php?id=%id%" target="content">Edit Folder</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="node_delete_confirm.php?id=%id%&t=fld" target="content">Delete Folder</a></li>
            </ul>
        </div>
    </div>
</div>

<div id="popup_rsc" class="menu" dcp:onpopup="handlePopupRsc">
    <div class="outer">
        <div class="inner">
            <ul dcp:no_click="true">
                <li><a fs:href_template="javascript:embedRsc(%id%, &quot;img&quot;)">Embed as Image</a></li>
                <li><a fs:href_template="javascript:embedRsc(%id%, &quot;link&quot;)">Embed as Link</a></li>
                <li><a fs:href_template="javascript:embedRsc(%id%, &quot;url&quot;)">Embed as URL</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="javascript:toggleMoveable(getResourceElem(%id%))">Move Resource</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="rsc_form.php?id=%id%" target="content">Edit Resource</a></li>
                <li><hr dcp:no_click="true"/></li>
                <li><a fs:href_template="node_delete_confirm.php?id=%id%&t=rsc" target="content">Delete Resource</a></li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
