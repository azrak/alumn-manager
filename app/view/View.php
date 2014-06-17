<?php

class View {

    private static $data;

    function __construct() {
        
    }

    public static function setData($data) {
        self::$data = $data;
    }

    public static function delData() {
        self::$data = NULL;
    }

    public static function showTemplate($tplFile) {

        if (file_exists($tplFile)) {
            $output = file_get_contents($tplFile);
            foreach (self::$data as $key => $val) {
                $replace = '{' . $key . '}';
                $output = str_replace($replace, $val, $output);
            }
            echo $output;
        }
    }

}
