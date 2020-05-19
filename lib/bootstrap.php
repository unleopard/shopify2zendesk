<?php
/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: bootstrap
 */

define('WEB_ROOT', str_replace("index.php", '', $_SERVER ['SCRIPT_NAME']));
define('CONTROLLERS_DIR', ROOT . DS . APP_DIR . DS . "controllers" . DS);
define('VIEWS_DIR', ROOT . DS . APP_DIR . DS . "views" . DS);
define('MODELS_DIR', ROOT . DS . APP_DIR . DS . "models" . DS);
define('WEBROOT', WEB_ROOT . "webroot" . DS);
define('CLASSES_DIR', ROOT . DS . APP_DIR . DS . "classes" . DS);
define('CORE_DIR', "core" . DS);

include CORE_DIR . 'standard.php';
include CORE_DIR . 'config.php';
include CORE_DIR . 'Controller.php';
include CORE_DIR . 'Autoloader.php';
include CORE_DIR . 'Model.php';
include CORE_DIR . 'Entities.php';
include CORE_DIR . 'Router.php';

require_once ROOT . DS . LIB_DIR . DS . "composer" . DS . "vendor" . DS . "autoload.php";

define('UPLOAD_DIR', ROOT . DS . "files" . DS);
define('REDIRECT_URL', (isset($_SERVER['REDIRECT_URL'])) ? $_SERVER['REDIRECT_URL'] : '/' . str_replace('Controller', '', CONTROLLER_DEFAULT));
define('SCRIPT_URL', isset($_SERVER['SCRIPT_URL']) ? $_SERVER['SCRIPT_URL'] : REDIRECT_URL);
define('BACKUP_DIR', ROOT . DS . 'databases');


class bootstrap
{
    private $_admin = null;
    private $ignore = array();
    private $is_admin = false;
    private $parametres = null;

    private $controller = null;
    private $action = null;

    private $request = null;
    private $url = null;
    private $get = null;

    function __construct()
    {
        $this->request = ltrim(SCRIPT_URL, '/');
        $this->url = str_replace(':443', '', $_SERVER['SCRIPT_URI']);

        Router::$url = $this->url;
        $router = Router::getOriginal();

        if (!is_null($router)) {
            $this->request = $router[1];
            $this->url = $router[0];
        }

        $this->_admin = 'admin';
        array_push($this->ignore, "gestion");

    }

    function init()
    {

        // clean url -->
        $perfect = self::perfect_url();
        if ($perfect !== true) redirect($perfect);
        // <-- clean url*/

        // declancher auloader
        new Autoloader ();

        // correction lien
        foreach ($this->ignore as $item) {
            if (preg_match('#' . $this->_admin . '/' . $item . '#', $this->request))
                $p = str_replace($this->_admin . '/' . $item, $this->_admin, $this->request);
        }

        $this->ready((isset($p)) ? $p : $this->request);

        // paramatres get
        $this->params();

        if (is_file(CONTROLLERS_DIR . $this->controller . ".php")) {

            $objet = new $this->controller ($this->get);

            if ($objet->autorezed($this->action)) {
                if (method_exists($objet, $this->action)) {
                    call_user_func_array(array(
                        $objet,
                        $this->action
                    ), (is_null($this->parametres) ? array() : $this->parametres));
                } else {
                    //redirect(ERREUR_CONTROLLER . DS . ACTION_NOT_FOUND);
                    die('action not found');
                }
            } else {
                if (!is_ajax()) {

                    die('permission');
                } else {
                    echo json_encode(array('success' => false, 'msg' => 'vous n avez pas permssion'));
                }

                exit ();
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            die('page not found');
            // redirect(ERREUR_CONTROLLER . DS . PAGE_NOT_FOUND);
        }
    }

    private function isadmin($params)
    {
        if ($params[0] == $this->_admin) {
            $this->is_admin = true;
            return true;
        }
        return false;
    }

    private function perfect_url()
    {

        // https www html
        if (startsWith($this->url, SITE_WEB) ) return true;
        elseif (endsWith($this->url, '.php.html')) return str_replace('.php.html', '.html', $this->url);
        else {
            $end = ltrim($_SERVER['SCRIPT_URL'], '/');
            $end = (empty($end)) ? str_replace('Controller', '', CONTROLLER_DEFAULT) : $end;

            return SITE_WEB . $end;
        }
    }

    private function ready($params)
    {
        // splite lien
        $params = preg_split("#[\/]#", $params);
        $params = $this->isadmin($params) ? array_splice($params, 1) : $params;

        // class, methode, parametres
        if (empty ($params [0])) {
            $this->controller = CONTROLLER_DEFAULT;
            $this->action = ACTION_DEFAULT;
        } else {
            $this->controller = $params [0] . CONTROLLER_SUF;
            $this->action = (isset ($params [1]) && !empty ($params [1])) ? $params [1] : ACTION_DEFAULT;
            if ($this->is_admin) $this->action = "{$this->_admin}_{$this->action}";
            $this->parametres = (isset ($params [1]) && !empty ($params [1])) ? array_splice($params, 2) : array();
        }
        return true;
    }

    private function params()
    {
        $requ_uri = (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : null;

        if (!is_null($requ_uri) && preg_match('~[?]~', $requ_uri)) {
            $requ_uri = substr($requ_uri, strpos($requ_uri, '?') + 1, strlen($requ_uri));
            foreach (explode('&', $requ_uri) as $elem) {
                $expl = explode('=', $elem);
                $this->get[$expl[0]] = (isset($expl[1])) ? $expl[1] : '';
            }
        } else $this->get = null;
    }

    private function is_protocole_secure()
    {
        return preg_match('~^https$~', $_SERVER['REQUEST_SCHEME']) ? true : false;
    }

}


$b = new bootstrap();
$b->init();