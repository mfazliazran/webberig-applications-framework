<?php
// Load settings
include("application/settings.php");

header("Content-type: text/css");

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modified) {
    header("HTTP/1.0 304 Not Modified");
    header ('Cache-Control:');
}

$css = file_get_contents($_GET['q']);

//print_r($_SERVER);

//******************************************************************
// Apply palette
//******************************************************************
if (isset($settings['CSSPalette']) && is_array($settings['CSSPalette']))
{
    foreach ($settings['CSSPalette'] as $key => $value)
    {
        $css = str_replace("{". $key ."}", $value, $css);
    }
}

//******************************************************************
// Minimize
//******************************************************************
$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

//******************************************************************
// Cache headers
//******************************************************************
$offset = 3600 * 24 * 365;
 
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
header("Cache-Control: ");
header("Last-Modified: ");
header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($_GET['q'])) . " GMT");

//******************************************************************
// Output with optional GZip compression
//******************************************************************
if (extension_loaded('zlib'))
{
    ob_start('ob_gzhandler');
}
echo $css;

if (extension_loaded('zlib'))
{
    ob_end_flush();
}
?>