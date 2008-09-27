<?php
    $id = @$_GET["id"];
    $type = @$_GET["t"];
    
    switch ($type)
    {
        case "cat":
            $type_str = "Category";
            $msg =  "
        Please confirm that you want to delete this category. All sub-categories,
        the documents under those categories and the pages under those documents
        will also be permanently deleted.
                ";
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
    
    $form_title = "Delete " . $type_str;
    $form_action = $type . "_delete.php";
    
    require "inc_form_hdr.php";
?>
    <script type="text/javascript">
    
    function updateNav(nav)
    {
        nav.selectElem(nav.get<?php echo $type_str; ?>Elem(<?php echo $id; ?>));
    }
    
    </script>

    <p><?php echo $msg; ?></p>
    
    <p class="warn">This operation is irreversible!</p>
    
    <p class="button">
        <input type="submit" value="Confirm"/>
    </p>
    
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>

<?php require "inc_form_ftr.php"; ?>
