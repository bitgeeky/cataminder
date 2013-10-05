<?php

include("php/tools.php");
include("php/config.php");
include("php/session.php");

// Unterverzeichnisse ermitteln
function GetMyFolders($dir_root, $dir, $depth) {
	global $SESSION;
	global $FOLDERS;
	$s = "";
	$a = array();
	
	// Alle Unterverzeichnisse ermitteln
	if ($depth >= 1) { $a = GetFolders($dir, explode(",", $SESSION["hidden_subfolder"])); }
	else { $a = GetFolders($dir, explode(",", $SESSION["hidden_folder"])); }

	if ($depth >= 1) { $s = @str_replace($dir_root, "", $dir); }

	for ($i = 0; $i < count($a); $i++) {
		$FOLDERS[] = array("name" => "&nbsp;&#953;&#8594;&nbsp;".$s.$a[$i], "path" => $dir.$a[$i]."/");
		GetMyFolders($dir_root, $dir.$a[$i]."/", $depth+1);
	}
}

$FOLDERS = array();
$FOLDERS[] = array("name" => GetRootFolder($SESSION["dir_root"]), "path" => $SESSION["dir_root"]);

// Alle Unterverzeichnisse ermitteln
GetMyFolders($SESSION["dir_root"], $SESSION["dir_root"], 0);

// Root-Verzeichnis überprüfen
$SELECT_STYLE = "";
if (!@is_dir(GetDocumentRoot().$FOLDERS[0]["path"])) { $SELECT_STYLE = " color:#d21034; font-weight:bold;"; }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
       "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SMMultiUpload</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="Jens Stolinski" />
<meta name="publisher" content="Jens Stolinski" />
<meta name="company" content="SYNASYS MEDIA" />

<!-- JavaScript -->
<script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="css/smmultiupload.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/menu.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/table.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/progressbar.css" type="text/css" media="screen" />

<!-- JavaScript -->
<script language="javascript" type="text/javascript" src="js/smmultiupload.js"></script>

</head>
<body scroll="no">

<div class="menu">
	<ul>
		<li><a id="m1" href="javascript:;" title="">
			<object id="smmultiupload" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="24" height="24" id="flashObject" align="middle">
				<param name="allowScriptAccess" value="always" />
				<param name="movie" value="flash/smmultiupload.swf" />
				<param name="quality" value="best" />
				<param name="wmode" value="transparent">
				<param name="menu" value="false" />
				<embed src="flash/smmultiupload.swf" wmode="transparent" quality="high" menu="false" width="24" height="24" name="smmultiupload" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object></a></li>
		<li><a id="m2" href="javascript:;" title="" onclick="SMMultiUpload_Clear2(); if (window.event){ window.event.returnValue = false; }"><img src="img/icon_remove_24x24.png" border="0" /></a></li>
		<li><img class="separator" src="img/icon_separator.png" border="0" /></li>
		<li><a id="m3" href="javascript:;" title="" onclick="SMMultiUpload_Upload(); if (window.event){ window.event.returnValue = false; }"><img src="img/icon_upload_24x24.png" border="0" /></a></li>
		<li><a id="m4" href="javascript:;" title="" onclick="SMMultiUpload_Cancel(); if (window.event){ window.event.returnValue = false; }"><img src="img/icon_stop_24x24.png" border="0" /></a></li>
		<li><img class="separator" src="img/icon_separator.png" border="0" /></li>
		<li><a id="m5" href="javascript:;" title="" onmouseover="SMMultiUpload_Info(); if (window.event){ window.event.returnValue = false; }" onmouseout="document.getElementById('upload_info').style.display='none';"><img src="img/icon_info_24x24.png" border="0" /></a><div id="upload_info"></div></li>
		<li><img class="separator" src="img/icon_separator.png" border="0" /></li>
		<li><select class="select" style="width:180px;<?php echo $SELECT_STYLE; ?>" id="select1" name="select1" size="1" onChange="SMMultiUpload_SetUploadDirectory2('<?php echo $SESSION["dir_root"]; ?>', '<?php GetCurrentPath($SESSION["dir_root"], $SESSION["dir_root"]); ?>', this.options[this.selectedIndex].value);">
			<?php
			for ($i = 0; $i < count($FOLDERS); $i++) {
				if ($i == 0) { echo "<option selected value=\"".$FOLDERS[$i]["path"]."\">".$FOLDERS[$i]["name"]."</option>"; }
				else { echo "<option value=\"".$FOLDERS[$i]["path"]."\">".$FOLDERS[$i]["name"]."</option>"; }
				echo $FOLDERS[$i]["path"];
			}
			?>
		</select></li>
	</ul>
</div>

<table style="width:100%; border-bottom:0px;" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th style="width:40px; text-align:center;"><script language="javascript" type="text/javascript">document.write(tinyMCEPopup.getLang('smmultiupload.table_column_caption_1', '?'));</script></th>
	<th style="width:16px; border-right:0px;">&nbsp;</th>
	<th style="border-right:0px;"><script language="javascript" type="text/javascript">document.write(tinyMCEPopup.getLang('smmultiupload.table_column_caption_2', '?'));</script></th>
	<th style="width:16px;">&nbsp;</th>
	<th style="width:100px; text-align:center;"><script language="javascript" type="text/javascript">document.write(tinyMCEPopup.getLang('smmultiupload.table_column_caption_3', '?'));</script></th>
	<th style="width:100px; text-align:center;"><script language="javascript" type="text/javascript">document.write(tinyMCEPopup.getLang('smmultiupload.table_column_caption_4', '?'));</script></th>
	<th style="width:120px; text-align:center;"><script language="javascript" type="text/javascript">document.write(tinyMCEPopup.getLang('smmultiupload.table_column_caption_5', '?'));</script></th>
	<th style="width:10px;">&nbsp;</th>
</tr>
</table>

<div id="main"></div>

<div id="message"></div>

<div id="statusbar">
	<div style="float:left; margin:2px;"><img src="img/icon_folder_20x20.png" border="0" /></div>
	<div id="statusbar_item1" style="float:left; padding-left:2px;"></div>
</div>

<script language="javascript" type="text/javascript">
<!--
// Event-Handler
window.onload = function() {

	// Oberfläche anpassen
	SMMultiUpload_WindowResize();
}

// Event-Handler: Überwachung der Fenstergröße
window.onresize = SMMultiUpload_WindowResize;

// Datei-Liste anzeigen
SMMultiUpload_ShowFileList();

// Menü: Hinweis hinzufügen
SMMultiUpload_MenuIni();

// Upload-Verzeichnis wechseln
SMMultiUpload_SetUploadDirectory2('<?php echo $SESSION["dir_root"]; ?>', '<?php echo GetCurrentPath($SESSION["dir_root"], $SESSION["dir_root"]); ?>', document.getElementById('select1').options[document.getElementById('select1').selectedIndex].value);

// Upload-Dateigröße festlegen
SMMultiUpload_SetUploadFilesize2(<?php echo $SESSION["upload_filesize"]; ?>);

// Upload-Dateitype festlegen
SMMultiUpload_SetUploadFiletype2('<?php echo $SESSION["upload_filetype"]; ?>');
//-->
</script>

</body>
</html>