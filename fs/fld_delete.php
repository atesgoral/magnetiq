<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    
    if (mag_db_connect())
    {
        $rsc_arr = mag_db_query_arr("SELECT id FROM resource WHERE folder_id = $id");
        
        if ($rsc_arr)
        {
            $rsc_list = implode(", ", $rsc_arr);
        }
        
        $result = "";
        
        if ($rsc_arr)
        {
            mag_db_query("DELETE FROM resource WHERE id IN ($rsc_list)");
            
            $result .= count($rsc_arr) . " resource" . (count($rsc_arr) > 1 ? "s" : "") . " in ";
        }
        
        mag_db_query("DELETE FROM folder WHERE id  = $id");
        
        $result .= "1 folder deleted.";
        
        mag_db_close();
    }
    else
    {
        $result = "Offline deleted.";
    }
?>

function updateNav(nav)
{
    nav.deleteElem(nav.PFX_FLD + <?php echo $id; ?>);
}

<?php require "inc_action_ftr.php"; ?>
