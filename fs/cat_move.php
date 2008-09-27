<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    $parent_id = $_POST["parent_id"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("category", "id = $id");
        $helper->addField("parent_id", $parent_id);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot move category: " . mysql_error());
        }
        
        mag_db_close();
    }
    
    $result = "Category moved.";
?>

function updateNav(nav)
{
    nav.moveElem(nav.PFX_CAT + <?php echo "$id, $parent_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
