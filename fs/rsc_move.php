<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_GET["id"];
    $folder_id = $_GET["parent_id"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("resource", "id = $id");
        $helper->addField("folder_id", $folder_id);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot move resource: " . mysql_error());
        }
        
        mag_db_close();
    }
    
    $result = "Resource moved.";
?>

function updateNav(nav)
{
    nav.moveElem(nav.PFX_RSC + <?php echo "$id, $folder_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>    
