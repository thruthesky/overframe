<?php

namespace of;
class Ajax {
    public function run() {
        header('Access-Control-Allow-Origin: *');

        switch( http_input('do') ) {
            case 'file-upload' : return data()->ajaxFileUpload();
            case 'file-delete' : return data()->ajaxFileDelete();
            case 'file-finish' : return data()->ajaxFileFinish();
        }

        return null;
    }
}