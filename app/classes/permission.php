<?php
/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: permissions
 */
class permission extends Entities
{

    private $id_permission;
    private $action;
    private $type_compte;
    private $active;

    function __construct($id_permission = null, $action = null, $type_compte = null, $active = null)
    {
        $this->id_permission = $id_permission;
        $this->action = $action;
        $this->type_compte = $type_compte;
        $this->active = $active;

        $this->table = "permissions";
        $this->primaryKey = "id_permission";
        $this->foreignKey = array(
            'action' => action::class,
            'type_compte' => typeCompte::class,
        );
        $this->metadata = array(
            'id_permission' => 'id_permission',
            'action' => 'action',
            'type_compte' => 'type_compte',
            'active' => 'active'
        );
    }

    public function getIdPermission()
    {
        return $this->id_permission;
    }

    public function setIdPermission($id_permission)
    {
        $this->id_permission = $id_permission;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getTypeCompte()
    {
        return $this->type_compte;
    }

    public function setTypeCompte($type_compte)
    {
        $this->type_compte = $type_compte;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function updateActive()
    {
        $this->active = ($this->active == 'Y') ? 'N' : 'Y';
    }

    public function tableShow()
    {
        // TODO: Implement tableShow() method.
    }
}