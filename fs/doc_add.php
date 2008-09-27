<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $document_title = $_POST["title"];
    $category_id = $_POST["category_id"];
    $alias = $_POST["alias"];
    $summary = $_POST["summary"];

    if (mag_db_connect())
    {
        $helper = new InsertHelper("document");
        $helper->addField("title", $document_title);
        $helper->addField("category_id", $category_id, false);
        $helper->addField("alias", $alias);
        $helper->addField("summary", $summary);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot insert new document: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        $document_id = mysql_insert_id();
        
        $page_title = "Page 1";
        
        $helper = new InsertHelper("page");
        $helper->addField("title", $page_title);
        $helper->addField("document_id", $document_id, false);
        $helper->addField("body", "...");
        $helper->addField("page_index", 1, false);
    
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot insert new Page: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        $page_id = mysql_insert_id();
    
        mag_db_close();    
    }
    else
    {
        $document_id = time() . "0";
        $page_id = time() . "1";
        $page_title = "Page 1";
    }
    
    $result = "Document added.";
?>

function updateNav(nav)
{
    nav.createDocument(<?php echo "$document_id, \"$document_title\", $category_id, \"$alias\", \"$summary\""; ?>);
    nav.createPage(<?php echo "$page_id, \"$page_title\", $document_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
