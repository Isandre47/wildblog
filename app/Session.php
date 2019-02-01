<?php

namespace App;

/**
 * Class session
 *
 * @package \App
 */

class Session{
    // Permet de démarrer session-start
    public function __construct(){
        session_start();
    }

}
