<?php
    require "inc_db.php";
    
    $id = @$_GET["id"];
    $document_id = @$_GET["document_id"];

    if (isset($id))
    {
        $form_title = "Edit Page";
        $form_action = "page_update.php";
        $form_button_text = "Update";
       
       
        if (mag_db_connect())
        {
            list($title, $body) = mag_db_query_row(
                "SELECT title, body " .
                "FROM page WHERE id = $id");
    
            mag_db_close();
        }
        else
        {
            $title = "Offline Title";
            $body = "Offline Body";
        }
    }
    else
    {
        $form_title = "Add Page";
        $form_action = "page_add.php";
        $form_button_text = "Add";
        
        $title = "";
        $body = "";
    }

    $tabs = "
    <a href=\"#\" onclick=\"return activateTab(this)\" ctp:page=\"form\" id=\"init_tab\"><span>Edit</span></a>
    <a href=\"#\" onclick=\"return activateTab(this)\" ctp:page=\"preview\" ctp:onactivate=\"showPreview\"><span>Preview</span></a>
    ";
    
    require "inc_form_hdr.php";
?>
    <script type="text/javascript">

    function updateNav(nav)
    {
<?php if (isset($id)): ?>    
        nav.selectElem(nav.getPageElem(<?php echo $id; ?>));
<?php else: ?>
        nav.clearSelection();
<?php endif; ?>
    }
    
    function initializeForm()
    {
        activateTab(ge("init_tab"));
        ge("fld_title").focus();
    }

    function embedStr(str)
    {
        ge("fld_body").value += str;
        ge("fld_body").focus();
    }
    
    function showPreview(anchor)
    {
        ge("preview_title").innerHTML = ge("fld_title").value;
        ge("preview_body").innerHTML = ge("fld_body").value;
    }
    
    </script>
    
    <p>
        <h3>Title</h3>
        <input type="text" name="title" class="fit_hor"
            value="<?php echo $title; ?>" id="fld_title"/>
    </p>

    <p>
        <h3>Body</h3>
        <textarea name="body" class="fit_hor" rows="15" id="fld_body"><?php echo htmlspecialchars($body); ?></textarea>
    </p>

    <p class="button">
        <input type="submit" value="<?php echo $form_button_text; ?>"/>
    </p>

<?php if (isset($id)): ?>    
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
<?php else: ?>
    <input type="hidden" name="document_id"
        value="<?php echo $document_id; ?>"/>
<?php endif; ?>

</div>

<div class="form preview" id="preview">
    <h2 id="preview_title"></h2>
    <div id="preview_body"></div>

<?php require "inc_form_ftr.php"; ?>
