<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    $title = $_POST["title"];
    $body = $_POST["body"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("page", "id = $id");
        $helper->addField("title", $title);
        $helper->addField("body", $body);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot update page: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        mag_db_close();
    }

    $result = "Page updated.";
?>

function updateNav(nav)
{
    nav.updatePage(<?php echo "$id, \"$title\""; ?>);
}

<?php require "inc_action_ftr.php"; ?>
