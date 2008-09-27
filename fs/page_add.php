<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $title = $_POST["title"];
    $document_id = $_POST["document_id"];
    $body = $_POST["body"];

    if (mag_db_connect())
    {
        $helper = new InsertHelper("page");
        $helper->addField("title", $title);
        $helper->addField("document_id", $document_id, false);
        $helper->addField("body", $body);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot insert new page: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        $id = mysql_insert_id();
        
        mag_db_close();    
    }
    else
    {
        $id = time();
    }
    
    $result = "Page added.";
?>

function updateNav(nav)
{
    nav.createPage(<?php echo "$id, \"$title\", $document_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
