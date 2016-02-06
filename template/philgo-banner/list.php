<style>
    [banner-id] {
        cursor:pointer;
    }
    .of-row img {
        max-width:100%;
    }

</style>
<script>
    $(function(){
        $('[banner-id] .content').click(function(){
            var id = $(this).parent().attr('banner-id');
            location.href='<?php echo url_action()?>&do=philgo-banner&what=upload&id='+id;
        });
        $('.delete-banner').click(function(e){
            e.preventDefault();
            var re = confirm('정말 삭제하시겠습니까?');
            if ( re ) {
                var id = $(this).parent().attr('banner-id');
                var url = "<?php echo ajax_endpoint()?>&do=philgo_banner&what=delete&id="+id;
                console.log(url);
                $.get(url, function(re){
                    if ( re['code'] ) return alert( re['message'] );
                    var id = re['data']['id'];
                    $("[banner-id='"+id+"']").hide(300);

                }, 'json');
            }
        });
    });
</script>
<?php

$no = data()->count("gid='philgo-banner'");
?>

<div class="of-row">
    <ul>
        <li><?php echo $no?> 개의 배너가 등록되어져 있습니다.</li>
        <li>배너 제목을 클릭하면 수정합니다.</li>
        </ul>

</div>



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
            if ( $image ) {
                $img = "<img src='" . $image->get('url') . "'>";
            }
            else $img = null;
        }
        $position = $banner->get('position');
        $date_from = $banner->get('date_from');
        $date_to = $banner->get('date_to');
        echo "
    <div class='of-row' banner-id='$id'>
        <div class='content'>
            $subject
            $img
            <div>
                $position $date_from ~ $date_to
            </div>
        </div>
        <div class='delete-banner'>
            <span class='btn btn-warning'>삭제</span> 배너를 삭제합니다.
        </div>
    </div>
    <hr>
    ";
    }
}
else {
    echo '<div class="alert alert-warning">등록된 배너가 없습니다.</div>';
}
