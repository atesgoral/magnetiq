<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    $name = $_POST["name"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("folder", "id = $id");
        $helper->addField("name", $name);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot update folder: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        mag_db_close();
    }

    $result = "Folder updated.";
?>

function updateNav(nav)
{
    nav.updateFolder(<?php echo "$id, \"$name\""; ?>);
}

<?php require "inc_action_ftr.php"; ?>
