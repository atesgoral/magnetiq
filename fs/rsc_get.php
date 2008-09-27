<?php
    require "inc_db.php";
    
    $id = @$_GET["id"];
    $n = @$_GET["n"]; 
        
    if (mag_db_connect())
    {
        list($data, $content_type, $size, $extension) = mag_db_query_row(
            "SELECT data, content_type, size, extension " .
            "FROM resource WHERE id = $id");
        
        mag_db_close();
    }
    else
    {
        $data = "Offline Data";
        $content_type = "text/plain";
        $size = 12;
        $extension = "txt";
    }
    
    header("Content-length: $size");
    header("Content-type: $content_type");
    
    if ($n != "0")
    {
        header("Content-Disposition: attachment; filename=$id.$extension");
    }
    
    echo $data;
    exit;
?>
