<?php

namespace of;
class Ajax {
    public function run() {
        header('Access-Control-Allow-Origin: *');

        switch( $doing = http_input('do') ) {
            /*
            case 'file-upload' : return data()->ajaxFileUpload();
            case 'file-delete' : return data()->ajaxFileDelete();
            case 'file-finish' : return data()->ajaxFileFinish();
            */
            default:
                $name = "of\\$doing";
                $obj = new $name();
                $obj->runAjax();
                json_error(-40444, "Nothing to do");
                return null;
        }
    }
}