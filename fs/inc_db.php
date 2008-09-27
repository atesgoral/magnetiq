<?php
  function mag_db_connect()
  {
    //return FALSE;
    
    global $link;
    
    if (isset($link))
      return TRUE;

    if ($link = mysql_connect("mysql18.ixwebhosting.com", "atesgor_fsack", "fs911pw"))
    {
      if (mysql_select_db("atesgor_fs"))
        return TRUE;
    }
    
    return FALSE;
  }

  function mag_db_close()
  {
    global $link;
    
    if (isset($link))
    {
      mysql_close($link);
      unset($link);
    }
  }
  
  function mag_db_query($sql)
  {
    if (!mag_db_connect())
     return FALSE;
     
    if ($result = mysql_query($sql))
      return $result;
    
    return FALSE;
  }

  function mag_db_build_js(&$result, $func, $maxrows = -1)
  {
    while ($maxrows-- && ($row = mysql_fetch_row($result)))
    {
      foreach ($row as $key => $value)
      {
        // if key is in list of items to escape
        $row[$key] = str_replace(
            array("\"", "\r", "\n"),
            array( "\\\"", "\\r", "\\n"),
            $value);
      }
      
      @$out .= $func . "(\"" . implode("\", \"", $row) . "\");\n";
    }

    return isset($out) ? $out : FALSE;
  }
  
  function mag_db_query_col($sql, $default, &$value)
  {
    if ($result = mag_db_query($sql))
    {
      if ($row = mysql_fetch_row($result))
      {
        $value = $row[0];
      }
      else
      {
        $value = $default;
      }

      mysql_free_result($result);
      
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  
  function mag_db_query_js($sql, $func, $maxrows = -1)
  {
    if ($result = mag_db_query($sql))
    {
      $js = mag_db_build_js($result, $func, $maxrows);
      mysql_free_result($result);
    }
    else
    {
      return FALSE;
    }

    return $js;
  }
  

  function mag_db_query_arr($sql)
  {
    if ($result = mag_db_query($sql))
    {
      while ($row = mysql_fetch_row($result))
        $items[] = $row[0];
      mysql_free_result($result);
  
      return isset($items) ? $items : FALSE;
    }
    else
    {
      return FALSE;
    }
  }

  function mag_db_query_list($sql)
  {
    if ($result = mag_db_query($sql))
    {
      while ($row = mysql_fetch_row($result))
        $items[] = $row[0];
      mysql_free_result($result);
  
      return isset($items) ? implode(", ", $items) : FALSE;
    }
    else
    {
      return FALSE;
    }
  }

  function mag_db_query_row($sql, $type = MYSQL_NUM)
  {
    $result = mag_db_query($sql);
    
    $ret = $result ? mysql_fetch_array($result, $type) : FALSE;
    
    if ($result)
      mysql_free_result($result);
    
    return $ret;
  }

  class InsertHelper
  {
    var $table, $fields, $values;
    
    function InsertHelper($table)
    {
      $this->table = $table;
      $this->reset();
    }
    
    function reset()
    {
      unset($this->fields);
      unset($this->values);
    }
    
    function addField($field, $value, $quote = true)
    {
      $this->fields[] = $field;
      $quotechr = $quote ? "'" : "";
      $this->values[] = $quotechr . mysql_escape_string($value) . $quotechr;
    }
    
    function getSQL()
    {
      return "INSERT INTO $this->table (" . implode(", ", $this->fields) . ") VALUES (" . implode(", ", $this->values) . ")";
    }
  }
  
  class UpdateHelper
  {
    var $table, $where;
    var $pairs = "";
    
    function UpdateHelper($table, $where)
    {
      $this->table = $table;
      $this->where = $where;
      
      //if (strlen($where))
        //$where = " WHERE " . $where;
    }
    
    function addField($field, $value, $quote = true)
    {
      $quotechr = $quote ? "'" : "";
      $this->pairs[] = $field . " = " . $quotechr . mysql_escape_string($value) . $quotechr;
    }
    
    function getSQL()
    {
      return "UPDATE $this->table SET " . implode(", ", $this->pairs) . " WHERE " . $this->where;
    }
  }
 
?>