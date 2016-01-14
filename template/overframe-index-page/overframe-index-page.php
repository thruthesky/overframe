<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo url_overframe()?>/etc/css/overframe.css">
<script src="<?php echo url_overframe()?>/etc/js/jquery-2.2.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
<?php
$url = url_action();
?>

<div class="of-page">
    <nav class="navbar navbar-light bg-faded">
        <div class="nav navbar-nav">
            <a class="nav-item nav-link active" href="#">Overframe
                <?php echo sys()->name()?>
                <span class="sr-only">(current)</span>
            </a>


            <a class="nav-item nav-link" href="<?php echo $url?>">Home</a>
            <a class="nav-item nav-link" href="<?php echo $url?>&do=test">Test</a>
            <a class="nav-item nav-link" href="<?php echo $url?>&do=model-list">Model List</a>
            <a class="nav-item nav-link" href="<?php echo $url?>&do=file-upload-test">File Upload Test</a>

            <a class="nav-item nav-link" href="<?php echo $url?>&do=philgo-banner">Banner</a>


        </div>
    </nav>


    <div class="of-content jumbotron">
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
            case 'philgo-banner' : return include sys()->template('philgo-banner');
            default : echo '<h1>No action</h1>';
        }
        ?>
    </div>

</div>