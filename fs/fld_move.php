<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_GET["id"];
    $parent_id = $_GET["parent_id"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("folder", "id = $id");
        $helper->addField("parent_id", $parent_id);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot move folder: " . mysql_error());
        }
        
        mag_db_close();
    }
    
    $result = "Folder moved.";
?>

function updateNav(nav)
{
    nav.moveElem(nav.PFX_FLD + <?php echo "$id, $parent_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
