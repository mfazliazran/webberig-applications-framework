<?php
$title = $settings["ApplicationName"];
if (isset($this->header))
{
$title .= " - " . $this->header->title;
}
$title .= " - Powered by Webberig.be";
?>
<title><?php echo $title;?></title>
<base href="<?php echo $settings["basePath"]; ?>" />
<?php
$this->IncludeLibraries('head');
?>
<script>
    function Redirect(path)
    {
        // Fix IE base path bug
        location.href = '<?php echo $settings['basePath'];?>' + path;
    }
    function Refresh()
    {
        window.location.reload();   
    }
</script>
<?php
Utility::CSS("application/layout/css/screen.css");
if (isset($_GET['module']))
{
    $js = "application/modules/" . $_GET['module'] . "/scripts.js";
    if (file_exists($js))
    {
        Utility::JS($js);
    }
}
?>