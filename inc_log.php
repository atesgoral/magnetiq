<?php

  function mag_log_append($topic, $data)
  {
  	return;
    $logroot = $_SERVER["SITE_HTMLROOT"] . "/";
      
    $now = time();

    $dir = $logroot . $topic;
    @mkdir($dir);
    if ($fp = fopen($dir . "/" . date("Ymd", $now) . ".log", "a"))
    {
      @fwrite($fp, date("H:i:s", $now) . "\t" . $data . "\n");
      @fclose($fp);
    }    
  }
?>