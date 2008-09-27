<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    
    if (mag_db_connect())
    {
        $cat_arr[] = $id;
        
        $sub_arr = mag_db_query_arr("SELECT id FROM category WHERE parent_id = $id");
        
        while ($sub_arr)
        {
            $cat_arr = array_merge($cat_arr, $sub_arr);
            $sub_arr = mag_db_query_arr("SELECT id FROM category WHERE parent_id IN (" . implode(", ", $sub_arr) . ")");
        }
        
        $cat_list = implode(", ", $cat_arr);
        
        $doc_arr = mag_db_query_arr("SELECT id FROM document WHERE category_id in ($cat_list)");
        $page_arr = FALSE;
            
        if ($doc_arr)
        {
            $doc_list = implode(", ", $doc_arr);
    
            $page_arr = mag_db_query_arr("SELECT id FROM page WHERE document_id in ($doc_list)");
            
            if ($page_arr)
            {
                $page_list = implode(", ", $page_arr);
            }
        }
        
        $result = "";
        
        if ($page_arr)
        {
            mag_db_query("DELETE FROM page WHERE id IN ($page_list)");
            //echo("DELETE FROM page WHERE id IN ($page_list)<br>");
            
            $result .= count($page_arr) . " page" . (count($page_arr) > 1 ? "s" : "") . " in ";
        }
        
        if ($doc_arr)
        {
            mag_db_query("DELETE FROM document WHERE id IN ($doc_list)");
            //echo("DELETE FROM document WHERE id IN ($doc_list)<br>");
    
            $result .= count($doc_arr) . " document" . (count($doc_arr) > 1 ? "s" : "") . " in ";;
        }
        
        mag_db_query("DELETE FROM category WHERE id IN ($cat_list)");
        //echo("DELETE FROM category WHERE id IN ($cat_list)<br>");
    
        $result .= count($cat_arr) . (count($cat_arr) > 1 ? " categories" : " category");
        $result .= " deleted.";
        
        mag_db_close();
    }
    else
    {
        $result = "Offline deleted.";
    }
?>

function updateNav(nav)
{
    nav.deleteElem(nav.PFX_CAT + <?php echo $id; ?>);
}

<?php require "inc_action_ftr.php"; ?>
