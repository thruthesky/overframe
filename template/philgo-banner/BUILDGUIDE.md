# 필고 배너

## TODO

* model 에 모든 것을 다 집어 넣는다.

## 예제

메인 중간 광고 표시


	$today = date('Y-m-d');
	$banners = banner()->getBanners("position='main-center' AND active<>'N' AND date_from<='$today' AND date_to>='$today' order by list_order desc limit 10");
	if ( $banners ) {
	    foreach( $banners as $banner ) {
	        $url_landing_page = $banner->get('url');
	        $image = data( $banner->get('fid') );
	        if ( $image ) {
	            $url_image = $image->get('url');
	            echo "<a href='$url_landing_page' target='_blank'><img src='$url_image'></a>";
	        }
	    }
	}
 







