<?php

include("config.php");

// Server Root-Pfad zurückgeben
function GetDocumentRoot() {
	global $SESSION;

	if ($SESSION["document_root"] != "") {
		$s = $SESSION["document_root"];
		if ($s[strlen($s)-1] == "/") { $s = substr($s, 0, -1); }
		return $s;
	}

	if (isset($_SERVER["DOCUMENT_ROOT"])) { return $_SERVER["DOCUMENT_ROOT"]; }
	else if (isset($_SERVER["APPL_PHYSICAL_PATH"])) {
		$s = $_SERVER["APPL_PHYSICAL_PATH"];
		$s = str_replace('\\', '/', $_SERVER["APPL_PHYSICAL_PATH"]);
		if ($s[strlen($s)-1] == "/") { $s = substr($s, 0, -1); }
		return $s;
	}
}

// Verzeichnis lesen und alle Ordner ermitteln
function GetFolders($dir, $hidden) {
	$folders = array();

	// Server-Pfad ermitteln
	$dir = GetDocumentRoot().$dir;

	// In Kleinbuchstaben umwandeln
	for ($i = 0; $i < count($hidden); $i++) { $hidden[$i] = strtolower($hidden[$i]); }

	// Leerzeichen entfernen
	for ($i = 0; $i < count($hidden); $i++) {
		$hidden[$i] = ltrim($hidden[$i]);
		$hidden[$i] = rtrim($hidden[$i]);
	}

	// Verzeichnisse ermitteln
	if ($dh = @opendir($dir)) {
		while($file = readdir($dh)) {
			if (!ereg("^\.+$", $file)) {
				if (is_dir($dir.$file) && !in_array(strtolower($file), $hidden)) { $folders[] = $file; }
			}
		}
		closedir($dh);
	}

	@sort($folders, SORT_STRING);

	// Server-Cache löschen
	clearstatcache();

	// Ordner-Array zurückgeben
	return $folders;
}

// Root-Verzeichnis ermitteln
function GetRootFolder($dir) {
	$a = explode('/', $dir);
	$s = $a[count($a)-2];
	unset($a);
	return $s;
}

// Aktuellen Verzeichnispfad zurückgeben
function GetCurrentPath($dir_root, $dir) {
	$a = explode('/', $dir_root);
	$s = $a[count($a)-2];
	unset($a);
	echo "/".$s."/".@str_replace($dir_root, "", $dir);
}

// RC4 Verschlüsselung
function RC4($data) {
	$s = array();
	$key = 'OMqMTGPJ63BP3xJDYxnu7EK6CTYhe1OlV3R1CitfeLKcQNpOXNWKYISDMrD06T8WXzuKrFWq3AxdCfPUhHtYrbPGXrGKQVpSZABuKWhfhGoExi9tyQT6Q4ry4xdmUVOR';
	
	for ($i = 0; $i < 256; $i++) { $s[$i] = $i; }

	$j = 0;
	$x;

	for ($i = 0; $i < 256; $i++) {
		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
	}
	
	$i = 0;
	$j = 0;
	$ct = '';
	$y;
	
	for ($y = 0; $y < strlen($data); $y++) {
		$i = ($i + 1) % 256;
		$j = ($j + $s[$i]) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
		$ct .= $data[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
	}
	
	return $ct;
}

?>