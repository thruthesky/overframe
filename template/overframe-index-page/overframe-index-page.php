<?php
$url = url_action();
?>
<h1>Overframe : <?php echo sys()->name()?></h1>
<a href="<?php echo $url?>">Home</a> |
<a href="<?php echo $url?>&do=test">Test</a> |
<a href="<?php echo $url?>&do=model-list">Model List</a> |
<a href="<?php echo $url?>&do=file-upload-test">File Upload Test</a>
<?php
switch( http_input('do') ) {
    case 'test' : return run_test();
    case 'model-list' :
    case 'install' :
    case 'uninstall' :
        include sys()->template('model-list');
        return;
    case 'file-upload-test' :
        include sys()->template('file-upload-test');
        return;
    default : echo '<h1>No action</h1>';
}
?>

