<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_GET["id"];
    $category_id = $_GET["parent_id"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("document", "id = $id");
        $helper->addField("category_id", $category_id);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot move document: " . mysql_error());
        }
        
        mag_db_close();
    }

    $result = "Document moved.";
?>

function updateNav(nav)
{
    nav.moveElem(nav.PFX_DOC + <?php echo "$id, $category_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
