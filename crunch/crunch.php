<?php
    function getNewFilename($x, $y, $width, $height, $imgs, $pixels, $bytes)
    {
        global $root;
        
        $filename_pre = $root . "/crunch/";
        $unique = 0;
        
        do
        {
            $new_item = date("YmdHis") . "_" .
                $x . "_" . $y . "_" .
                $width . "_" . $height . "_" .
                $imgs . "_" . $pixels . "_" . $bytes . "_" .
                str_pad($unique, 2, "0", STR_PAD_LEFT) . ".jpg";
            $new_filename = $filename_pre . $new_item;
            $unique++;
        }
        while (file_exists($new_filename));
        
        return $new_filename;
    }

    $root = @$_SERVER["DOCUMENT_ROOT"];

    if (!strlen($root))
        $root = dirname($_SERVER["PATH_TRANSLATED"]);

    $files = glob("*.jpg");
  
    if ($files)
    {
        rsort($files);
        $old_filename = $files[0];
    }
    else
    {
        $old_filename = false;
    }
    
    $max_width = 400;
    $max_height = 400;

    $file = @$_FILES["m"];

    //if (isset($file)) ...
    
    $up_filename = $file["tmp_name"];

    if (strlen($up_filename))
    {
        if ($up_size = @getimagesize($up_filename))
        {
            $up_width = $up_size[0];
            $up_height = $up_size[1];
            
            $up_pixels = $up_width * $up_height;
            
            $pathinfo = pathinfo($file["name"]);
            
            switch (strtoupper($pathinfo["extension"]))
            {
                case "JPG":
                case "JPEG":
                    $up_img = imagecreatefromjpeg($up_filename);
                    break;
                case "PNG":
                    $up_img = imagecreatefrompng($up_filename);
                    break;
                case "GIF":
                    $up_img = imagecreatefromgif($up_filename);
                    break;
                default:
                    exit();
            }
            // if old_filename etc.

            $up_filesize = filesize($up_filename);
            //$old_filesize = filesize($old_filename);
            //$old_filesize = 0;

            $old_img = @imagecreatefromjpeg($old_filename);

            if ($old_img != "")
            {
                $old_size = getimagesize($old_filename);
                
                $old_width = $old_size[0];
                $old_height = $old_size[1];
                            
                $old_stats = explode("_", $old_filename);
                
                $old_imgs = $old_stats[5];
                $old_pixels = $old_stats[6];
                $old_bytes = $old_stats[7];

                $new_scale = 1;
                
                $tail = (rand(1, 2) == 1);
                
                if ($old_width / $old_height >= 1)
                {
                    $new_width = $old_width;
                    $up_scale = $old_width / $up_width;
                    $new_height = $old_height + $up_height * $up_scale;
                    $off_x = 0;
                    $off_y = $tail ? $old_height : ($up_height * $up_scale);
                    
                    if ($new_height > $max_height)
                    {
                        $new_scale = $max_height / $new_height;
                        $new_width *= $new_scale;
                        $new_height = $max_height;
                    }
                }
                else
                {
                    $up_scale = $old_height / $up_height;
                    $new_width = $old_width + $up_width * $up_scale;
                    $new_height = $old_height;
                    $off_x = $tail ? $old_width : ($up_width * $up_scale);
                    $off_y = 0;
                    
                    if ($new_width > $max_width)
                    {
                        $new_scale = $max_width / $new_width;
                        $new_height *= $new_scale;
                        $new_width = $max_width;
                    }
                }

                $new_img = imagecreatetruecolor($new_width, $new_height);

                if ($tail)
                {
                    $old_cpy_x = 0;
                    $old_cpy_y = 0;

                    $up_cpy_x = round($off_x * $new_scale);
                    $up_cpy_y = round($off_y * $new_scale);
                }
                else
                {
                    $old_cpy_x = round($off_x * $new_scale);
                    $old_cpy_y = round($off_y * $new_scale);

                    $up_cpy_x = 0;
                    $up_cpy_y = 0;
                }

                $old_cpy_width = round($old_width * $new_scale);
                $old_cpy_height = round($old_height * $new_scale);
                
                imagecopyresampled($new_img, $old_img,
                    $old_cpy_x, $old_cpy_y,
                    0, 0,
                    $old_cpy_width, $old_cpy_height,
                    $old_width, $old_height);
                
                imagecopyresampled($new_img, $up_img,
                    $up_cpy_x, $up_cpy_y,
                    0, 0,
                    round($up_width * $up_scale * $new_scale),
                    round($up_height * $up_scale * $new_scale),
                    $up_width, $up_height);

                imagedestroy($old_img);
                imagedestroy($up_img);
                
                imagejpeg($new_img, getNewFilename($old_cpy_x, $old_cpy_y,
                    $old_cpy_width, $old_cpy_height,
                    $old_imgs + 1, $old_pixels + $up_pixels,
                    $old_bytes + $up_filesize), 75);

                imagedestroy($new_img);
            }
            else
            {
                // if $up_width > 400 or $up_height > 400 etc. then resize!!!
                move_uploaded_file($up_filename,
                    getNewFilename(0, 0, 0, 0, 1, $up_pixels, $up_filesize));
            }
        }
    }
    
    header("Location: http://magnetiq.com/crunch/serve.php?i=$new_item");
?>