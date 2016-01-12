<?php

namespace of;


class System {

    const ci3 = 'codeigniter3';
    static $count_log = 0;

    public function __construct()
    {

    }

    /**
     *
     * Returns the name of the parent framework.
     *
     *
     * @return null
     */
    public function find() {
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


    /**
     * @param $str
     * @return int|void
     */
    public static function log ( $str )
    {
        $str = is_string($str) ? $str : print_r( $str, true );
        file_put_contents ( PATH_DEBUG_LOG, self::$count_log++ . ' : ' . $str . "\n", FILE_APPEND );
    }

}
