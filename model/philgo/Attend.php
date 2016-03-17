<?php
namespace of\philgo;
use of\Philgo;

class Attend extends Philgo
{

    const max_attend = 10;
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('philgo_attend');
    }

    // create attend record.
    /**
     *
     *
     */
    public function attend() {

        $idx = user()->getIdx();
        if ( empty($idx) ) json_error(-40443, "로그인을 하십시오.");


        $id = user()->getUsername();
        $meta = new PhilgoMeta();
        $nick = $meta->get("google_store.$id");
        if ( empty($nick) ) json_error(-40401, "앱 평가를 먼저 하셔야 출석을 할 수 있습니다.");





        $date = date('Ymd');
        $entity = $this->load("user_id=$idx AND date=$date");
        if ( $entity ) {
            $count = $this->count_consecutive_attend();
            if ( $count >= self::max_attend ) {
                json_error(-40448, "출석 이벤트를 완료하였습니다.");
            }
            else {
                $ymd = preg_replace('/([0-9]{4})([0-9]{2})([0-9]{2})/', "$1년 $2월 $3일", $date);
                json_error(-40448, "{$ymd}에 출근 도장을 이미 찍으셨습니다. 연속으로 $count 번 출근 도장을 찍으셨습니다.");
            }
        }

        $entity = $this
            ->create()
            ->sets( array(
                'user_id' => $idx,
                'date' => $date
            ) )
            ->save();
        if ( $entity ) {
            $count = $this->count_consecutive_attend();
            if ( $count >= 5 ) {
                $meta = new PhilgoMeta();
                $meta->set("attend.complete.$idx", time());
                json_success( array('code'=>1,'message'=>"축하합니다. 출석 이벤트를 완료하였습니다.") );
            }
            else json_success( array('count' => $count ) );
        }
        else {
            json_error(-40041, "출근 실패");
        }
    }


    /**
     *
     *
     * @note 호출 예: http://philgo.org/?module=overframe&action=index&model=philgo.attend.collect&idx_member=test1016&session_id=a4c4d294aa2a91f25c326ae36d16d941&page=front&mobile=false&platform=isNotCordova
     *
     */
    public function collect() {
        $my_idx = user()->getID();
        $ret = array();
        for( $i = 9; $i >= 0; $i -- ) {
            $date = date('Ymd', time() - 60 * 60 * 24 * $i );
            $row = $this->row("user_id=$my_idx AND date=$date");
            $row['ymd'] = preg_replace('/([0-9]{4})([0-9]{2})([0-9]{2})/', "$1-$2-$3 ", $date);
            $ret[] = $row;
        }
        json_success( $ret );
    }

    public function count_consecutive_attend() {
        $my_idx = user()->getID();
        for ( $i = 0; $i < 1000; $i ++ ) {
            $date = date('Ymd', time() - 60 * 60 * 24 * $i );
            $row = $this->row("user_id=$my_idx AND date=$date");
            if ( empty($row) ) return $i;
        }
        return 0;
    }

}

