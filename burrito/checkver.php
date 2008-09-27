<?php
$LATEST_VERSION = "1.0.b";
$LATEST_BUILD = "2002.10.17.23.37";

$Version = @$_GET["v"];
$Build = @$_GET["b"];
$Cookie = @$_GET["c"];

//Addr = Request.ServerVariables("REMOTE_ADDR")
//Fwd = Request.ServerVariables("HTTP_X_FORWARDED_FOR") 
//if Len(Fwd) > 0 then Addr = Fwd & " / " & Addr

?>
<html>

<head>
<title>Burrito - Version Check</title>
<link rel="stylesheet" type="text/css" href="burrito.css">
</head>

<body>

<div class="top">
<a href="."><img src="i/logo.gif" width="270" height="60" border="0" alt="Burrito"></a>
</div>

<div class="sep"><img src="i/s.gif" height="3" alt=""></div>

<div class="nav"><a href="." class="nav">Home</a> | <a href="downloads.php" class="nav">Downloads</a> | <a href="screens.php" class="nav">Screenshots</a> | <a href="help.htm" class="nav">Help</a></div>

<div class="pad">
<table width="250" border="0" cellpadding="0" cellspacing="0">
  <tr><td>
    <b>Your Version:</b> <?php echo $Version; ?><br />
    <b>Your Build:</b> <?php echo $Build; ?><br />
    <p />
<?php if (strcmp($Build, $LATEST_BUILD) > 0): ?>
    <font color="green" class="large">Your Burrito is fresh!</font><br />
    If you need the installer anyway,<br />
    head over to <a href="downloads.php">Downloads</a>.
<?php else: ?>
    <b>Latest Version:</b> <?php echo $LATEST_VERSION; ?><br />
    <b>Latest Build:</b> <?php echo $LATEST_BUILD; ?><br />
    <p />
    <font color="red" class="large">Your Burrito is stale!</font><br />
    Head over to <a href="downloads.php">Downloads</a> to get<br />
    the latest version.
<?php endif; ?>
  </td></tr>
</table>
</div>

<div class="banner">
<a href="downloads.php"><img src="i/download.gif" width="135" height="180" border="0" alt="Download Burrito Now!"></a>
</div>

</body>

</html>