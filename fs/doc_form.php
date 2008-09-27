<?php
    require "inc_db.php";
    
    $id = @$_GET["id"];
    $category_id = @$_GET["category_id"];
   
    if (isset($id))
    {
        $form_title = "Edit Document";
        $form_action = "doc_update.php";
        $form_button_text = "Update";
        
        if (mag_db_connect())
        {       
            list($title, $alias, $summary) = mag_db_query_row(
                "SELECT title, alias, summary " .
                "FROM document WHERE id = $id");
        
            mag_db_close();
        }
        else
        {
            $title = "Offline Title";
            $alias = "offline_alias";
            $summary = "Offline Summary";
        }
    }
    else
    {
        $form_title = "Add Document";
        $form_action = "doc_add.php";
        $form_button_text = "Add";

        $title = "";
        $alias = "";
        $summary = "";
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
        nav.selectElem(nav.getDocumentElem(<?php echo $id; ?>));
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
        ge("fld_summary").value += str;
        ge("fld_summary").focus();
    }
    
    function showPreview(anchor)
    {
        ge("preview_title").innerHTML = ge("fld_title").value;
        ge("preview_summary").innerHTML = ge("fld_summary").value;
    }

    </script>

    <p>
        <h3>Title</h3>
        <input type="text" name="title" class="fit_hor"
            value="<?php echo $title; ?>" id="fld_title"/>
    </p>

    <p>
        <h3>Alias</h3>
        <input type="text" name="alias" class="fit_hor"
            value="<?php echo $alias; ?>"/>
    </p>

    <p>
        <h3>Summary</h3>
        <textarea name="summary" class="fit_hor" rows="10" id="fld_summary"><?php echo htmlspecialchars($summary); ?></textarea>
    </p>

    <p class="button">
        <input type="submit" value="<?php echo $form_button_text; ?>"/>
    </p>

<?php if (isset($id)): ?>    
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
<?php else: ?>
    <input type="hidden" name="category_id"
        value="<?php echo $category_id; ?>"/>
<?php endif; ?>

</div>

<div class="form preview" id="preview">
    <h2 id="preview_title"></h2>
    <div id="preview_summary"></div>

<?php require "inc_form_ftr.php"; ?>
