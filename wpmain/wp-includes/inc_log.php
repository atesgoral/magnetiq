<?php

  function mag_log_append($topic, $data)
  {
    $logroot = "/home/magnetiq/public_html/logs/";
      
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