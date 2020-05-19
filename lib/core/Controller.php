<?php
/**
 * Created by Majoch abdessamad.
 * Date: 19/05/2020
 * Mail: majoch.abdessamad@gmail.com
 * Autor: majoch abdessamad
 * File: Controller
 */
class Controller
{
    var $params = array();
    var $layout = 'default';
    var $titre = '';
    protected $parametres = null;

    function __construct($params = null)
    {
        $this->parametres = $params;

        // langues
        if (isset($this->parametres['langue']) && in_array($this->parametres['langue'], array('fr', 'en'))) {
            // change langue
            $_SESSION['langue'] = $this->parametres['langue'];
        } elseif (!isset($this->parametres['langue']) && !$this->is_set_langue()) {
            // create new
            $_SESSION['langue'] = 'fr';
        }

        if (str_replace(CONTROLLER_SUF, "", get_class($this)) != "erreur") {
            $this->loadModel("login");
            $this->login->setDefault();
        }
    }

    function set($array)
    {
        $this->params = array_merge($this->params, $array);
    }

    function setTitre($title)
    {
        $this->titre = $title;
    }

    function render($view_name, $layout = true)
    {
        extract($this->params);

        $view = str_replace("Controller", "", get_class($this));
        $title = (empty($this->titre)) ? $view : $this->titre;
        ob_start();

        include_once ROOT . DS . LIB_DIR . DS . "langs" . DS . $_SESSION['langue'] . ".php";

        if (is_file(VIEWS_DIR . $view . DS . $view_name . '.php'))
            include VIEWS_DIR . $view . DS . $view_name . '.php';

        $contenu = ob_get_clean();
        if ($this->layout == false || $layout == false)
            echo $contenu;
        else {
            if (is_file(VIEWS_DIR . "layout" . DS . $this->layout . ".php")) {
                include VIEWS_DIR . "layout" . DS . $this->layout . ".php";
            }
        }
    }


    function loadModel($name)
    {
        $includNom = $name . 'Model';

        if (class_exists($includNom)) $this->$name = new $includNom ();
        else redirect(ERREUR_CONTROLLER . DS . SYSTEM_ERREUR);
    }

    function autorezed($action)
    {
        if (str_replace(CONTROLLER_SUF, "", get_class($this)) == "erreur") {
            return true;
        } else {
            $perm = $this->login->permission(str_replace(CONTROLLER_SUF, "", get_class($this)), $action);
            if (isset ($perm ['nombre']) && $perm ['nombre'] == 1) {
                $this->layout = $perm ['page'];
                return true;
            } else {
                return false;
            }
        }
    }


    private function is_set_langue()
    {
        return (isset($_SESSION['langue']) && !empty($_SESSION['langue'])) ? true : false;
    }
}
