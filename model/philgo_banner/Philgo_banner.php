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

        if ( empty(hi('owner') ) ) json_error(-400009, "광고주를 입력하십시오.");
        if ( empty(hi('code') ) ) json_error(-400009, "광고 위치를 선택하십시오.");
        if ( empty(hi('fid') ) ) json_error(-400009, "배너 사진을 올리십시오.");
        if ( empty(hi('date_from') ) ) json_error(-400009, "광고 시작 날짜를 선택하십시오.");
        if ( empty(hi('date_to') ) ) json_error(-400009, "광고 끝 날짜를 선택하십시오.");
        if ( empty(hi('subject') ) ) json_error(-400009, "광고 제목을 입력하십시오.");
        if ( empty(hi('url') ) ) json_error(-400009, "광고 페이지 URL 을 입력하십시오.");

        if ( hi('fid') ) {
            data(hi('fid'))->finish();
        }
        if ( hi('id') ) {
            $banner = new Philgo_banner();
            $banner->load(hi('id'));
        }
        else {
            $banner = new Philgo_banner();
            $banner->create();
        }
        $banner
            ->set('position', hi('code'))
            ->set('fid', hi('fid'))
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

}
