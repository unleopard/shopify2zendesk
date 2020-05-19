<?php

/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: type Compte
 */

class typeCompte extends Entities
{

    private $id_type_compte;
    private $type_compte;

    function __construct($id_type_compte = null, $type_compte = null)
    {
        $this->id_type_compte = $id_type_compte;
        $this->type_compte = $type_compte;

        $this->table = "type_comptes";
        $this->primaryKey = "id_type_compte";
        $this->metadata = array(
            'id_type_compte' => 'id_type_compte',
            'type_compte' => 'type_compte'
        );
    }

    public function getIdTypeCompte()
    {
        return $this->id_type_compte;
    }

    public function setIdTypeCompte($id_type_compte)
    {
        $this->id_type_compte = $id_type_compte;
    }

    public function getTypeCompte()
    {
        return $this->type_compte;
    }

    public function setTypeCompte($type_compte)
    {
        $this->type_compte = $type_compte;
    }

    public function option()
    {
        return '<option value="' . $this->id_type_compte . '">' . $this->type_compte . '</option>';
    }

    public function tableShow()
    {
        $_ul = '<ul class="li_inline"><li><a href="#" onclick="permission(' . $this->id_type_compte . ');"><i class="fa fa-cog" aria-hidden="true"></i> liste des permissions</a></li></ul>';
        $type_cmpt = ($this->type_compte == 'nobody')? 'internautes':$this->type_compte;

        return '<tr id="type_' . $this->id_type_compte . '"><td>' . $this->id_type_compte . '</td><td>' . ucwords($type_cmpt) . '</td><td>' . $_ul . '</td></tr>';
    }

    public function table_tr()
    {
        $_ul = '<ul class="li_inline"><li><a href="#" onclick="permission(' . $this->id_type_compte . ');"><i class="fa fa-cog" aria-hidden="true"></i> liste des permissions</a></li></ul>';
        $type_cmpt = ($this->type_compte == 'nobody')? 'internautes':$this->type_compte;

        return '<td>' . $this->id_type_compte . '</td><td>' . ucwords($type_cmpt) . '</td><td>' . $_ul . '</td>';
    }

    public function show_option($select = '')
    {
        $_select = ($this->id_type_compte == $select) ? 'selected' : '';
        return '<option value="' . $this->id_type_compte . '" ' . $_select . '>' . $this->type_compte . '</option>';
    }
}