<?php
$url = $_SERVER["REQUEST_URI"];
$break = Explode('/', $url);
$file = $break[count($break) - 1];
$cachefile = "cache/" . $sitename . ".infinetia-" . $file . ".html";

// Serve from the cache if it is younger than $cachetime
if (file_exists($cachefile)) {
    echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
    include($cachefile);
    exit;
}
ob_start(); // Start the output buffer
?>
