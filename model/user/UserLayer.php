<?php
namespace of\user;
use of\System;

class UserLayer
{



    /**
     * 로그인 과정을 진행한다.
     *
     * 입력 정보는 HTTP input 의 idx_member 와 session_id 로 들어오며,
     * 회원 정보를 $sys->member 에 저장하고,
     * 회원 번호를 리턴한다.
     *
     * 이것은 module/ajax/DataLayer.php 의 회원 로그인과 비슷하며,
     *
     * ajax 의 model=.... 와 같이 호출하는 경우, overframe/ajax/Ajax.php 의 run() 에 의해서 호출된다.
     *
     * @return mixed 회원번호 또는 ajax 에러 메세지.
     */
    public function login() {

        global $sys;

        $in = http_input();
        $in['remember'] = 'Y';
        sys()->log(" =========> UserLayer::login() in: ");

        if ( empty($in['idx_member']) ) return FALSE;

        if ( isset($in['idx_member']) && $in['idx_member'] && isset($in['session_id']) ) {
            $member = $sys->member->get( $in['idx_member'] );
            if ( empty($member) )json_error(-508,"User not found. Wrong idx_member.");
            if ( $this->session_id($member) != $in['session_id'] ) json_error(-507,"Wrong user session id. Your IP and location information has been reported to admin.");
        }
        else {
            sys()->log(" =====> No. login. in[idx_member] and in[action] is not member_register_submit,  in[id], in[password] is empty. ");
            return FALSE;
        }


        $sys->member->idx = $member['idx'];
        $sys->member->info = $member;
        return $sys->member->idx;

    }

    public function session_id($m) {
        global $sys;
        return $sys->member->login_auth($m['idx'], $m['id'], $m['password']);
    }




    public function getLogin() {
        if ( sys()->isSapcms1() ) {
            return login();
        }
        else return false;
    }


    public function isAdmin() {
        if ( sys()->isSapcms1() ) {
            return admin();
        }
        else return null;
    }

    public function getUsername() {
        if ( sys()->isSapcms1() ) {
            return login('id');
        }
        else return null;
    }

    public function getID() {
        if ( sys()->isSapcms1() ) {
            return login('idx');
        }
        else return null;
    }
    public function getIdx() {
        return $this->getID();
    }

    public function getName() {
        if ( sys()->isSapcms1() ) {
            return login('name');
        }
        else return null;
    }

    public function getEmail() {
        if ( sys()->isSapcms1() ) {
            return login('email');
        }
        else return null;
    }
}
