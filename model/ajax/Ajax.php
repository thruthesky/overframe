<?php

namespace of;
/**
 * Class Ajax
 * @package of
 *
 * @code do 다음에 점을 찍으면 모델 이름과 클래스 이름이 된다.
 *
    <?php echo ajax_endpoint()?>&do=entity.ItemList
 *
 * @endcode
 */
class Ajax {
    public function run() {
        header('Access-Control-Allow-Origin: *');

        switch( $doing = http_input('do') ) {
            default:
                if ( strpos($doing, '.') ) {
                    $doing = str_replace('.', '\\', $doing);
                }
                else {
                    $doing = ucfirst($doing);
                }
                $name = "of\\$doing";
                $obj = new $name();
                $obj->runAjax();
                json_error(-40444, "Nothing to do");
                return null;
        }
    }
}
