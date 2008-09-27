<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    $name = $_POST["name"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("resource", "id = $id");
        $helper->addField("name", $name);
        
        // only update data if file uploaded!
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot update resource: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        mag_db_close();
    }

    $result = "Resource updated.";
?>

function updateNav(nav)
{
    nav.updateResource(<?php echo "$id, \"$name\""; ?>);
}

<?php require "inc_action_ftr.php"; ?>    
