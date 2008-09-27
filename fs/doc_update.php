<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    $title = $_POST["title"];
    $alias = $_POST["alias"];
    $summary = $_POST["summary"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("document", "id = $id");
        $helper->addField("title", $title);
        $helper->addField("alias", $alias);
        $helper->addField("summary", $summary);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot update document: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        mag_db_close();
    }

    $result = "Document updated.";
?>

function updateNav(nav)
{
    nav.updateDocument(<?php echo "$id, \"$title\", \"$alias\", \"$summary\""; ?>);
}

<?php require "inc_action_ftr.php"; ?>
