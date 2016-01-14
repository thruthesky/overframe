<h1>Overframe : <?php echo sys()->name() ?></h1>

<a href="?action=index">Home</a> |
<a href="?action=test">Test</a> |
<a href="?action=model-list">Model List</a> |
<a href="?action=file-upload-test">File Upload Test</a>

<?php

switch( http_input('action') ) {
    case 'test' : return run_test();
    case 'model-list' :
        include sys()->template('model-list');
        return;
    case 'file-upload-test' :
        include sys()->template('file-upload-test');
        return;
    default : echo '<h1>Wrong action</h1>';
}
