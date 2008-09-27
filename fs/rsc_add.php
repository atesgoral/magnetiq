<?php
    require "inc_action_hdr.php";
    require "inc_db.php";

    $name = $_POST["name"];
    $folder_id = $_POST["folder_id"];

    $file = $_FILES["upload"];
    $data = file_get_contents($file["tmp_name"]);
    $content_type = $file["type"];
    $size = $file["size"];
    $path_parts = pathinfo($file["name"]);
    $extension = $path_parts["extension"];

    if (mag_db_connect())
    {
        $helper = new InsertHelper("resource");
        $helper->addField("name", $name);
        $helper->addField("folder_id", $folder_id, false);
        $helper->addField("data", $data);
        $helper->addField("content_type", $content_type);
        $helper->addField("size", $size);
        $helper->addField("extension", $extension);
        
        if (!mag_db_query($helper->getSQL()))
        {
            die("Cannot insert new resource: " . mysql_error() . "\n" . $helper->getSQL());
        }
        
        $id = mysql_insert_id();
        
        mag_db_close();    
    }
    else
    {
        $id = time();
    }

    $result = "Resource added.";
?>

function updateNav(nav)
{
    nav.createResource(<?php echo "$id, \"$name\", $folder_id"; ?>);
}

<?php require "inc_action_ftr.php"; ?>
