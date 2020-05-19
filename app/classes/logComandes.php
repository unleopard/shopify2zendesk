<?php
/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: log commandes
 */

class logComandes extends Entities
{

    private $id;
    private $data;
    private $date_ajout;

    /**
     * logComandes constructor.
     * @param $id
     * @param $data
     * @param $date_ajout
     */
    public function __construct($id = null, $data = null, $date_ajout = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->date_ajout = $date_ajout;

        $this->primaryKey = 'id';
        $this->table = 'log_comandes';
        $this->metadata = [
            'id' => 'id',
            'data' => 'data',
            'date_ajout' => 'date_ajout',
        ];

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
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return null
     */
    public function getDateAjout()
    {
        return $this->date_ajout;
    }

    /**
     * @param null $date_ajout
     */
    public function setDateAjout($date_ajout)
    {
        $this->date_ajout = $date_ajout;
    }

    public function tableShow()
    {
        // TODO: Implement tableShow() method.
    }
}