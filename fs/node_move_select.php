<?php
    $id = @$_GET["id"];
    $type = @$_GET["t"];
    
    switch ($type)
    {
        case "cat":
            $type_str = "Category";
            $msg =  "Please click the category that you'd like to move this
                category under";
            break;

        case "doc":
            $type_str = "Document";
            $msg =  "
        Please confirm that you want to delete this document. All pages under this document
        will also be permanently deleted.
                ";
            break;

        case "page":
            $type_str = "Page";
            $msg =  "
        Please confirm that you want to delete this page.
                ";
            break;

        case "fld":
            $type_str = "Folder";
            $msg =  "
        Please confirm that you want to delete this folder. All resources under this folder
        will also be permanently deleted.
                ";
            break;

        case "rsc":
            $type_str = "Resource";
            $msg =  "
        Please confirm that you want to delete this resource.
                ";
            break;
    }
    
    $form_title = "Move " . $type_str;
    $form_action = $type . "_move.php";
    
    require "inc_form_hdr.php";
?>
    <script type="text/javascript">
    
    function updateNav(nav)
    {
        nav.selectElem(nav.get<?php echo $type_str; ?>Elem(<?php echo $id; ?>));
    }
    
    function performMove(parent_id)
    {
        var fld = ge("fld_parent_id");
        
        fld.value = parent_id;
        fld.form.submit();
    }
    
    </script>

    <p><?php echo $msg; ?></p>
    
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
    <input type="hidden" name="parent_id" id="fld_parent_id"/>

<?php require "inc_form_ftr.php"; ?>
