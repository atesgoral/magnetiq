<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_GET["id"];
    $document_id = $_GET["parent_id"];
    
    if (mag_db_connect())
    {
        $helper = new UpdateHelper("page", "id = $id");
        $helper->addField("document_id", $document_id);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot move page: " . mysql_error());
        }
        
        mag_db_close();
    }

    $result = "Page moved.";
?>

function updateNav(nav)
{
    nav.moveElem(nav.PFX_PAGE + <?php echo "$id, $document_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
