<?php
    require "inc_db.php";
    
    $id = @$_GET["id"];
    $parent_id = @$_GET["parent_id"];

    $name = "";
    
    if (isset($id))
    {
        $form_title = "Edit Category";
        $form_action = "cat_update.php";
        $form_button_text = "Update";
        
        if (mag_db_connect())
        {
            mag_db_query_col("SELECT name FROM category WHERE id = $id", "", $name);
        
            mag_db_close();
        }
        else
        {
            $name = "Offline Name";
        }
    }
    else
    {
        $form_title = "Add Category";
        $form_action = "cat_add.php";
        $form_button_text = "Add";
    }
   
    require "inc_form_hdr.php";
?>
    <script type="text/javascript">
        
    function updateNav(nav)
    {
<?php if (isset($id)): ?>    
        nav.selectElem(nav.getCategoryElem(<?php echo $id; ?>));
<?php else: ?>
        nav.clearSelection();
<?php endif; ?>
    }
    
    function initializeForm()
    {
        ge("first").focus();   
    }
    
    </script>

    <p>
        <h3>Name</h3>
        <input type="text" name="name" class="fit_hor"
            value="<?php echo $name; ?>" id="first"/>
    </p>
    
    <p class="button">
        <input type="submit" value="<?php echo $form_button_text; ?>"/>
    </p>

<?php if (isset($id)): ?>    
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
<?php else: ?>
    <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>"/>
<?php endif; ?>

<?php require "inc_form_ftr.php"; ?>
