<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $name = $_POST["name"];
    $parent_id = $_POST["parent_id"];

    if (mag_db_connect())
    {
        $helper = new InsertHelper("folder");
        $helper->addField("name", $name);
        $helper->addField("parent_id", $parent_id, false);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot insert new folder: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        $id = mysql_insert_id();
        
        mag_db_close();    
    }
    else
    {
        $id = time();
    }
    
    $result = "Folder added.";
?>

function updateNav(nav)
{
    nav.createFolder(<?php echo "$id, \"$name\", $parent_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
