<?php


?>
<style>
    .jumbotron {
        border-radius: 0!important;
    }
</style>

<h1 class="display-5">Philgo Banner</h1>
<p class="lead">
    <a class='btn btn-secondary' href="<?php echo url_action()?>&do=philgo-banner&what=list">List</a>
    <a class='btn btn-secondary' href="<?php echo url_action()?>&do=philgo-banner&what=upload">Upload</a>
</p>
<hr class="m-y-2">

<?php
$what = http_input('what', 'list');
if ( $what ) include $what . '.php';
?>

