<?php

namespace App\Helpers;

class Nounces
{
    /**
     * Check nounces is valid
     * @param $nounce
     * @param $action
     * @return bool
     */
    public static function check($nounce, $action){
        if ( ! isset( $nounce ) || ! wp_verify_nonce( $nounce, $action )  ) {
            return false;
        }
        return true;
    }
}