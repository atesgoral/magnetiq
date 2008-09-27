<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    $name = $_POST["name"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("category", "id = $id");
        $helper->addField("name", $name);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot update category: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        mag_db_close();
    }
    
    $result = "Category updated.";
?>

function updateNav(nav)
{
    nav.updateCategory(<?php echo "$id, \"$name\""; ?>);
}

<?php require "inc_action_ftr.php"; ?>
