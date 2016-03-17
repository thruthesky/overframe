<?php


?>
<style>
    .jumbotron {
        border-radius: 0!important;
    }
</style>

<div class="of-page">
<a class="btn btn-secondary" href="<?php echo url_action()?>&do=philgo-banner&what=list">배너 목록</a> |
<a class="btn btn-secondary" href="<?php echo url_action()?>&do=philgo-banner&what=upload">배너 등록</a>
</div>


<?php
$what = http_input('what', 'list');
if ( $what ) include $what . '.php';
?>

