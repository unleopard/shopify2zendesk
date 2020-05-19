<?php

/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: Autoloader
 */

class Autoloader
{

    private $dirs = array();

    function __construct()
    {
        $this->dirs = array(
            CONTROLLERS_DIR, MODELS_DIR, VIEWS_DIR, CLASSES_DIR, ROOT . DS . LIB_DIR . DS . CORE_DIR . DS
        );
        spl_autoload_register(array($this, "loader"));
    }

    public function loader($class_name)
    {

        foreach ($this->dirs as $dir) {
            $file = "{$dir}{$class_name}.php";

            if (is_readable($file)) {
                require_once $file;
                return;
            }
        }
    }
}
