<?php
namespace of;

class Philgo_banner extends Node
{
    private $in;
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('philgo_banner');
        $this->in = http_input();
    }


    /**
     *
     */
    public function install() {
        $this->init();
        $this->addColumn('position', 'varchar', 64);
        $this->addColumn('fid', 'int unsigned');
        $this->addColumn('owner', 'varchar', 64);
        $this->addColumn('active', 'char');
        $this->addColumn('date_from', 'varchar', 32);
        $this->addColumn('date_to', 'varchar', 32);
        $this->addColumn('subject', 'varchar');
        $this->addColumn('list_order', 'int');
        $this->addColumn('url', 'varchar');
    }

    public function uninstall()
    {
        $this->uninit();
    }



    public function runAjax() {

        $in = http_input();
        sys()->log('Philgo_banner:;runAjax()');
        sys()->log($in);

        if ( $in['what'] == 'upload' ) $this->ajaxUpload();
        else if ( $in['what'] == 'delete') $this->ajaxDelete();

    }

    public function hasAccess()
    {
        $id = user()->getID();
        if ( user()->isAdmin() || $id == 'thruthesky' || $id == 'adman' ) return true;
        else return false;
    }

    /**
     *
     * 등록된 배너를 리턴한다.
     *
     * @param $position
     * @return array
     * @code 특정 position 의 배너를 얻기
     * $banners = banner()->getBanners("main-center");
     * di($banners);
     * @endcode
     *
     * @code 쿼리를 통한 배너를 얻기. * 입력 파라메타에 공백이 있으면 쿼리로 인식.
         $banners = banner()->getBanners("position='main-center' and active<>'N' order by list_order desc limit 10");
         di(count($banners));
     * @endcode
     *
     * @code 진행 중인 광고 목록 얻기
        $today = date('Y-m-d');
        $banners = banner()->getBanners("position='main-center' AND active<>'N' AND date_from<='$today' AND date_to>='$today' order by list_order desc limit 10");
        di(count($banners));
     * @endcode
     *
     */
    public function getBanners($position)
    {
        if ( strpos($position, ' ') ) $banners = $this->loadQuery("$position");
        else $banners = $this->loadQuery("position='$position'");
        if ( $banners ) {
            foreach( $banners as $banner ) {
                $fid = $banner->get('fid');
                if ( $fid ) {
                    $image = data($fid);
                    if ( $image ) {
                        $banner->set('url_image', $image->get('url'));
                    }
                }
            }
        }
        return $banners;
    }

    private function ajaxUpload()
    {

        if ( ! hi('owner') ) json_error(-400009, "광고주를 입력하십시오.");
        if ( ! hi('code') ) json_error(-400009, "광고 위치를 선택하십시오.");
        //if ( ! hi('fid') ) json_error(-400009, "배너 사진을 올리십시오.");
        if ( ! hi('date_from') ) json_error(-400009, "광고 시작 날짜를 선택하십시오.");
        if ( ! hi('date_to') ) json_error(-400009, "광고 끝 날짜를 선택하십시오.");
        if ( ! hi('subject') ) json_error(-400009, "광고 제목을 입력하십시오.");
        if ( ! hi('url') ) json_error(-400009, "광고 페이지 URL 을 입력하십시오.");


        if ( hi('id') ) {
            $banner = new Philgo_banner();
            $banner->load(hi('id'));
        }
        else {
            $banner = new Philgo_banner();
            $banner->create();
        }


        if ( hi('fid') ) {
            data(hi('fid'))->finish();
            $banner->set('fid', hi('fid'));
        }

        $banner
            ->set('position', hi('code'))
            ->set('owner', hi('owner'))
            ->set('active', hi('active'))
            ->set('date_from', hi('date_from'))
            ->set('date_to', hi('date_to'))
            ->set('subject', hi('subject'))
            ->set('list_order', hi('list_order'))
            ->set('url', hi('url'))
            ->save();
        json_success(array('id'=>$banner->get('id')));

    }

    private function ajaxDelete()
    {
        $id = http_input('id', 0);
        if ( $id ) {
            $banner = $this->load($id);
            if ( $banner ) {
                $fid = $banner->get('fid');
                if ( $fid ) {
                    $data = data($fid);
                    if ( $data ) {
                        $data->delete();
                    }
                }
                $banner->delete();
                json_success( array('id'=>$id ));
            }
            else json_error(-1235, 'No banner by that id');
        }
        else json_error(-1234, 'No id provided');
    }

}

