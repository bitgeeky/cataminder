<?php

include("config.php");
include("tools.php");

if(!isset($_FILES)) { exit(); }

$dir = GetDocumentRoot().$_GET["dir"];

// Verzeichnis �berpr�fen
if (is_dir(substr($dir, 0, -1))) {

	// Alle �bertragenen Dateien speichern
	foreach($_FILES as $file) {
		if(!is_uploaded_file($file['tmp_name'])) { continue; }

		// Datei speichern
		@move_uploaded_file($file['tmp_name'], $dir.$file['name']);

		// Dateirechte anpassen
		$uploadedFile = $dir.$file['name'];
		chmod($uploadedFile, $CONFIG["chmod_file"]);
	}
}
else {
	header("HTTP/1.0 500 Internal Server Error");
}

?> 