<?php

namespace of;


class System {

    const ci3 = 'codeigniter3';
    const sapcms1 = 'sapcms1';
    static $count_log = 0;


    static $list_model = [];

    public function __construct()
    {

    }


    /**
     * @param $list
     *
     * @code
        sys()->setModels( glob(OVERFRAME_DIR . "/model/*", GLOB_ONLYDIR) );
        di(sys()->getModels());
     * @endcode
     */
    public static function setModelPaths( $list ) {
        self::$list_model = $list;
    }

    public static function getModelPaths( ) {
        return self::$list_model;
    }

    public static function getModels() {
        $names = array();
        foreach ( sys()->getModelPaths() as $model ) {
            $names[] = pathinfo($model, PATHINFO_BASENAME);
        }
        return $names;
    }

    /**
     *
     * Returns the name of the parent framework.
     *
     *
     * @return null
     */
    public function find() {
        if ( defined('DIR_DATA_WIDGET') ) return self::sapcms1;
        if ( function_exists('get_instance') ) return self::ci3;
        else return null;
    }

    /**
     * Returns TRUE if the parent framework is codeigniter 3.
     * @return bool
     */
    public function isCodeIgniter3() {
        return $this->find() == System::ci3;
    }
    public function isSapcms1() {
        return $this->find() == System::sapcms1;
    }


    /**
     * @param $str
     * @return int|void
     */
    public function log ( $str )
    {
        $str = is_string($str) ? $str : print_r( $str, true );
        file_put_contents ( path_debug_log(), self::$count_log++ . ' : ' . $str . "\n", FILE_APPEND );
    }

    public function template($module_name) {
        return dir_overframe() . "/template/$module_name/$module_name.php";
    }

    public function url_ajax_endpoint()
    {
        global $url_overframe_ajax_endpoint;
        return $url_overframe_ajax_endpoint;
    }


}
