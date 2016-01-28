<?php
namespace of\philgo;
use of\Philgo;

class Point extends Philgo
{

    public function __construct()
    {
        parent::__construct();
    }
    public function update() {
        $idx = user()->getIdx();
        if ( $idx ) {
            $meta = new PhilgoMeta();
            $stamp = $meta->get("attend.complete.$idx");
            if ( $stamp ) {
                // 1 분 이내에 중복 신청이 안되도록 한다.
                if ( $stamp < time() - 60 ) {

                    // 총 포인트가 10만 점이 넘지 않도록 한다.
                    $point = $meta->get("total.event.point.$idx");
                    if ( $point > 100000 ) json_success(array('code'=>-40470, 'message'=>"한도 초과: 포인트는 50,000 점까지만 획득 가능합니다."));

                    global $sys;
                    $d = array(
                        'idx_member'		=> $idx,
                        'idx_member_from'	=> $idx,
                        'point'				=> 77,
                        'idx_post'			=> 0,
                        'etc'				=> 'point event 2016-01-26',
                    );
                    $sys->point->update( $d );
                    $meta->set("attend.complete.$idx", time());
                    $meta->set("total.event.point.$idx", $point + 77);
                    json_success(array('code'=>0, 'message'=>"OK"));
                }
                else {
                    $left = 60 - ( time() - $stamp );
                    json_success(array('code'=>-40450, 'message'=>"너무 빠른 포인트 증가 시도입니다. $left 초 남았음."));
                }
            }
            else json_success(array('code'=>-40449, 'message'=>"출석 이벤트를 완료하십시오."));
        }
        else json_success(array('code'=>-40104, 'message'=>"로그인을 하십시오."));
    }
}
