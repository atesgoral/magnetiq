<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    
    if (mag_db_connect())
    {
        $page_arr = mag_db_query_arr("SELECT id FROM page WHERE document_id = $id");
        
        if ($page_arr)
        {
            $page_list = implode(", ", $page_arr);
        }
        
        $result = "";
        
        if ($page_arr)
        {
            mag_db_query("DELETE FROM page WHERE id IN ($page_list)");
            //echo("DELETE FROM page WHERE id IN ($page_list)<br>");
            
            $result .= count($page_arr) . " page" . (count($page_arr) > 1 ? "s" : "") . " in ";
        }
        
        mag_db_query("DELETE FROM document WHERE id  = $id");
        //echo("DELETE FROM document WHERE id IN ($doc_list)<br>");
        
        $result .= "1 document deleted.";
        
        mag_db_close();
    }
    else
    {
        $result = "Offline deleted.";
    }
?>

function updateNav(nav)
{
    nav.deleteElem(nav.PFX_DOC + <?php echo $id; ?>);
}
    
<?php require "inc_action_ftr.php"; ?>
