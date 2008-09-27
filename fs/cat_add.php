<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $name = $_POST["name"];
    $parent_id = $_POST["parent_id"];

    if (mag_db_connect())
    {
        $helper = new InsertHelper("category");
        $helper->addField("name", $name);
        $helper->addField("parent_id", $parent_id, false);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot insert new category: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        $id = mysql_insert_id();
        
        mag_db_close();    
    }
    else
    {
        $id = 42;
    }
    
    $result = "Category added.";
?>

function updateNav(nav)
{
    nav.createCategory(<?php echo "$id, \"$name\", $parent_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
