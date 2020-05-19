<?php

/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: action
 */

class action extends Entities
{

    private $id_action;
    private $module;
    private $action;
    private $page;
    private $permission;
    private $list_layout = array();

    function __construct($id_action = null, $module = null, $action = null, $page = null)
    {
        $this->id_action = $id_action;
        $this->module = $module;
        $this->action = $action;
        $this->page = $page;

        $this->table = "actions";
        $this->primaryKey = "id_action";
        $this->foreignKey = array(
            'module' => module::class,
        );
        $this->metadata = array(
            'id_action' => 'id_action',
            'module' => 'module',
            'action' => 'action',
            'page' => 'page'
        );
    }

    public function getIdAction()
    {
        return $this->id_action;
    }

    public function setIdAction($id_action)
    {
        $this->id_action = $id_action;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return mixed
     */
    public function getListLayout()
    {
        return $this->list_layout;
    }

    /**
     * @param mixed $list_layout
     */
    public function setListLayout($list_layout)
    {
        $this->list_layout = $list_layout;
    }


    public function tableShow()
    {
        $activ = (empty($this->permission->getIdPermission()) || $this->permission->getActive() == 'N') ? '' : 'data-default="1"';
        $id_p = (empty($this->permission->getIdPermission())) ? '-1' : $this->permission->getIdPermission();

        $_act = startsWith($this->action, 'admin_') ? '[admin]' . str_replace('_', ' ', str_replace('admin_', '', $this->action)) : str_replace('_', ' ', $this->action);

        return "<div class=\"row_switch\"><label>{$_act }</label><div class=\"div_right\"><span class=\"easyswitch\" {$activ} id='{$id_p}' action='{$this->id_action }' data-callback=\"onSwitch\"></span></div></div>";
    }

    public function show()
    {
        $_act = str_replace('admin_', '[admin] ', $this->action);

        return "<tr><td>{$_act}</td><td>{$this->option()}</td></tr>";
    }

    public function option()
    {
        $str = '<select id="' . $this->id_action . '" onchange="update_action_layout(this);">';
        foreach ($this->list_layout as $lyt) {
            $_selected = ($lyt == $this->page) ? ' selected="selected" ' : '';
            $str .= '<option value="' . $lyt . '"' . $_selected . '>' . $lyt . '</option>';
        }
        $str .= '</select>';
        return $str;
    }
}