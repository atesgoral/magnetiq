<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $id = $_POST["id"];
    
    if (mag_db_connect())
    {
        mag_db_query("DELETE FROM resource WHERE id  = $id");
        
        mag_db_close();
    }
    
    $result = "1 resource deleted.";
?>

function updateNav(nav)
{
    nav.deleteElem(nav.PFX_RSC + <?php echo $id; ?>);
}

<?php require "inc_action_ftr.php"; ?>
