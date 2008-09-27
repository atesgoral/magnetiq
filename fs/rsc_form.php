<?php
    require "inc_db.php";
    
    $id = @$_GET["id"];
    $folder_id = @$_GET["folder_id"];

    if (isset($id))
    {
        $form_title = "Edit Resource";
        $form_action = "rsc_update.php";
        $form_button_text = "Update";
        
        if (mag_db_connect())
        {
            list($name, $content_type, $size, $extension) = mag_db_query_row(
                "SELECT name, content_type, size, extension " .
                "FROM resource WHERE id = $id");
        
            mag_db_close();
            
            $preview = isset($id) && ereg("^image|text/", $content_type);
        }
        else
        {
            $name = "Offline Name";
            $content_type = "text/plain";
            $size = "100";
            $extension = "txt";

            $preview = isset($id) && ereg("^image|text/", $content_type);
        }
    }
    else
    {
        $form_title = "Add Resource";
        $form_action = "rsc_add.php";
        $form_button_text = "Add";
        
        $name = "";
        
        $preview = false;
    }

    $tabs = "<a href=\"#\" onclick=\"return activateTab(this)\" ctp:page=\"form\" id=\"init_tab\"><span>Edit</span></a>\n";
    
    if ($preview)
    {    
        $tabs .= "<a href=\"#\" onclick=\"return activateTab(this)\" ctp:page=\"preview\" ctp:onactivate=\"showPreview\"><span>Preview</span></a>\n";
    }
   
    $form_xtra_attrs = "enctype=\"multipart/form-data\"";
    
    require "inc_form_hdr.php";
?>
    <script type="text/javascript">
    
    function updateNav(nav)
    {
<?php if (isset($id)): ?>    
        nav.selectElem(nav.getResourceElem(<?php echo $id; ?>));
<?php else: ?>
        nav.clearSelection();
<?php endif; ?>
    }
    
    function initializeForm()
    {
        activateTab(ge("init_tab"));
        ge("preview_file").innerHTML = ge("inf_file").innerHTML;
        ge("fld_name").focus();
    }
    
    function showPreview(anchor)
    {
        ge("preview_name").innerHTML = ge("fld_name").value;
    }
    
    </script>

    <p>
        <h3>Name</h3>
        <input type="text" name="name" class="fit_hor"
            value="<?php echo $name; ?>" id="fld_name"/>
    </p>

    <p>
        <h3>Upload</h3>
        <div class="file">
            <input type="file" name="upload"/>
        </div>
    </p>

<?php if (isset($id)): ?>    
    <p id="inf_file">
        <a href="rsc_get.php?id=<?php echo $id; ?>"><?php echo "$id.$extension"; ?></a>
        (<?php echo number_format($size); ?> bytes, <?php echo $content_type; ?>)
    </p>
<?php endif ?>

    <p class="button">
        <input type="submit" value="<?php echo $form_button_text; ?>"/>
    </p>

<?php if (isset($id)): ?>    
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
<?php else: ?>
    <input type="hidden" name="folder_id"
        value="<?php echo $folder_id; ?>"/>
<?php endif ?>

<?php if ($preview): ?>
</div>

<div class="form preview" id="preview">
    <h2 id="preview_name"></h2>
<?php if (ereg("^image/", $content_type)): ?>
    <img src="rsc_get.php?id=<?php echo $id; ?>" class="preview_img"/>
<?php elseif (ereg("^text/", $content_type)): ?>
    <iframe name="data" height="250" src="rsc_get.php?id=<?php echo $id; ?>&n=0" class="preview_iframe"></iframe>
<?php endif; ?>
    <p id="preview_file"></p>
</div>
<?php endif; ?>

<?php require "inc_form_ftr.php"; ?>
