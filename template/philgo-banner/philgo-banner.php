<?php


?>
<style>
    .jumbotron {
        border-radius: 0!important;
    }
</style>


<a href="<?php echo url_action()?>&do=philgo-banner&what=list">배너 목록</a> |
<a href="<?php echo url_action()?>&do=philgo-banner&what=upload">배너 등록</a>



<?php
$what = http_input('what', 'list');
if ( $what ) include $what . '.php';
?>

