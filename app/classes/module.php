<?php

/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: module
 */

class module extends Entities
{

    private $id_module;
    private $module;
    private $list_action = null;

    function __construct($id_module = null, $module = null)
    {
        $this->id_module = $id_module;
        $this->module = $module;
        $this->list_action = array();

        $this->table = "modules";
        $this->primaryKey = "id_module";
        $this->metadata = array(
            'id_module' => 'id_module',
            'module' => 'module'
        );
    }

    public function getIdModule()
    {
        return $this->id_module;
    }

    public function setIdModule($id_module)
    {
        $this->id_module = $id_module;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getListAction()
    {
        return $this->list_action;
    }

    public function setListAction($action)
    {
        array_push($this->list_action, $action);
    }

    public function tableShow()
    {

    }

}