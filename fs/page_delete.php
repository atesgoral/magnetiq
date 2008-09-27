<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    
    if (mag_db_connect())
    {
        mag_db_query("DELETE FROM page WHERE id  = $id");
        
        mag_db_close();
    }
    
    $result = "1 page deleted.";
?>

function updateNav(nav)
{
    nav.deleteElem(nav.PFX_PAGE + <?php echo $id; ?>)
}

<?php require "inc_action_ftr.php"; ?>
