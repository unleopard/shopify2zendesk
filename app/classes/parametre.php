<?php

/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: parametres
 */
class parametre extends Entities
{
    private $id;
    private $key;
    private $value;
    private $alterable;

    function __construct($id = null, $key = null, $value = null, $alterable = null)
    {
        $this->id = $id;
        $this->key = $key;
        $this->value = $value;
        $this->alterable = $alterable;

        $this->table = 'parametres';
        $this->primaryKey = 'id';
        $this->metadata = array(
            'id' => 'id',
            'key' => 'key',
            'value' => 'value',
            'alterable' => 'alterable'
        );
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param null $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return null
     */
    public function getAlterable()
    {
        return $this->alterable;
    }

    /**
     * @param null $alterable
     */
    public function setAlterable($alterable)
    {
        $this->alterable = $alterable;
    }

    public function tableShow()
    {
        // TODO: Implement tableShow() method.
    }
}