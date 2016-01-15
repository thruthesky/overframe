<style>
    [banner-id] {
        cursor:pointer;
    }
    .of-row img {
        width:100%;
    }
</style>
<script>
    $(function(){
        $('[banner-id]').click(function(){
            var id = $(this).attr('banner-id');
            location.href='<?php echo url_action()?>&do=philgo-banner&what=upload&id='+id;
        });
    });
</script>
<?php

$no = data()->count("gid='philgo-banner'");
?>

You have <?php echo $no?> banners.



<hr>

<?php

/*
 * $datas = data()->loadQuery("gid='philgo-banner'");

foreach ( $datas as $data ) {
    $url = $data->get('url');
    echo "<div class='of-row'><img src='$url'></div>";
}
*/

$philgo_banner = new \of\Philgo_banner();
$banners = $philgo_banner->loadAll();
if ( $banners ) {
    foreach ( $banners as $banner ) {
        $id = $banner->get('id');
        $subject = $banner->get('subject');
        $img = null;
        if ( $banner->get('fid') ) {
            $image = data($banner->get('fid'));
            if ( $image->is() ) {
                $img = "<img src='" . $image->get('url') . "'>";
            }
        }
        $position = $banner->get('position');
        $date_from = $banner->get('date_from');
        $date_to = $banner->get('date_to');
        echo "
    <div class='of-row' banner-id='$id'>
    $subject
    $img
    <div>
    $position $date_from ~ $date_to
</div>
    </div>
    <hr>
    ";
    }
}
else {
    echo '<div class="alert alert-warning">등록된 배너가 없습니다.</div>';
}
