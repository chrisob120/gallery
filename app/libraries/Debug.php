<?php

class Debug {

    /*
     * Takes the print_r PHP function and formats for easier viewing
     *
     * @return void
     */
    public static function print_rci($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

}