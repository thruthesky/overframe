<style>
    .of-row img {
        width:100%;
    }
</style>
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
        $subject = $banner->get('subject');
        $img = null;
        if ( $banner->get('fid') ) {
            $image = data($banner->get('fid'));
            if ( $image->is() ) {
                $img = "<img src='" . $image->get('url') . "'>";
            }
        }
        echo "
    <div>
    $subject
    $img
    </div>
    ";
    }
}
else {
    echo '<div class="alert alert-warning">등록된 배너가 없습니다.</div>';
}