<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_GET["id"];
    $vec = $_GET["v"]; 
    
    if (mag_db_connect())
    {
        list($doc_id, $idx) = mag_db_query_row("SELECT document_id, page_index FROM page WHERE id  = $id");
        
        $other_idx = $idx + $vec;
      
        mag_db_query("UPDATE page SET page_index = $idx WHERE document_id = $doc_id AND page_index = $other_idx");
        mag_db_query("UPDATE page SET page_index = $other_idx WHERE id = $id");
        
        mag_db_close();
    }

    $result = "Pages swapped.";
?>

function updateNav(nav)
{
    nav.swapElem(nav.PFX_PAGE + <?php echo "$id, $vec"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
