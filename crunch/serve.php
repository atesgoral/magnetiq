<?php
    $files = glob("*.jpg");

    if (!$files)
        exit();
        
    rsort($files);
    
    $item = @$_GET["i"];
    $next = false;
    
    if (isset($item))
    {
        $found = false;
        
        do
        {
            if (current($files) == $item)
            {
                $found = true;
                $next = next($files);
                break;
            }
        }
        while (next($files));
        
        if (!$found)
            $item = false;
    }
    else
    {
        $item = false;
    }


    if (!$item)
    {
        $item = $files[0];
        
        if (count($files) > 1)
            $next = $files[1];
    }

    if ($item)
    {
        $root = @$_SERVER["DOCUMENT_ROOT"];
    
        if (!strlen($root))
            $root = dirname($_SERVER["PATH_TRANSLATED"]);
            
        $filename = $root . "/crunch/" . $item;
        
        $filesize = filesize($filename);
        $imagesize = @getimagesize($filename);
        
        $width = $imagesize[0];
        $height = $imagesize[1];
        $left = 61 + (400 - $width) / 2;
        $top = 61 + (400 - $height) / 2;
        
        //$stats = explode("_", $filename);
        //$imgs = $stats[5];
        //$pixels = $stats[6];
        //$bytes = $stats[7];

        echo("<html><head><title>{$item}_{$filesize}</title></head><body>");
        
        if ($next)
            echo("<a href=\"serve.php?i=$next\" target=\"server\" onClick=\"return onImgClick()\">");
            
        echo("<img src=\"$item\" width=\"$width\" height=\"$height\" alt=\"\" class=\"crunch\" style=\"top: $top; left: $left;\" id=\"img\">");
        
        if ($next)
            echo("</a>");
            
        echo("</body></html>");
    }
?>