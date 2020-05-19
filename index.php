<?php
/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: index
 */

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '128M');
set_time_limit(0);

error_reporting(E_ALL);

date_default_timezone_set('Africa/Casablanca');

define("APP_DIR", "app");
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(__FILE__));

define("LIB_DIR", "lib");

if (!file_exists(LIB_DIR . DS . 'bootstrap.php'))
    die ('<h3>Erreur bootstrap</h3> bootstrap n\'existe pas ou endommag&eacute;');

include LIB_DIR . DS . 'bootstrap.php';


?>