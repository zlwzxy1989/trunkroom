<?php

/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Cron extends Controller {

    public function before() {
        if (!$this->isCommandLineInterface()) {
            exit('CLI mode only');
        }
    }

    /**
     * check if is cli mode
     * @return boolean
     */
    function isCommandLineInterface() {
        return (php_sapi_name() === 'cli');
    }

}
