<?php
/**
 * Description of entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: shopify
 */

class shopify extends Entities
{
    private $id;
    private $commande;
    private $tiket;
    private $s_tiket;
    private $date_creation;

    /**
     * shopify constructor.
     * @param $id
     * @param $commande
     * @param $tiket
     * @param $s_tiket
     * @param $date_creation
     */
    public function __construct($id = null, $commande = null, $tiket = null, $s_tiket = null, $date_creation = null)
    {
        $this->id = $id;
        $this->commande = $commande;
        $this->tiket = $tiket;
        $this->s_tiket = $s_tiket;
        $this->date_creation = $date_creation;

        $this->table = 'shopify';
        $this->primaryKey = 'id';
        $this->metadata = [
            'id' => 'id',
            'commande' => 'commande',
            'tiket' => 'tiket',
            's_tiket' => 's_tiket',
            'date_creation' => 'date_creation'
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
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * @param null $commande
     */
    public function setCommande($commande)
    {
        $this->commande = $commande;
    }

    /**
     * @return null
     */
    public function getTiket()
    {
        return $this->tiket;
    }

    /**
     * @param null $tiket
     */
    public function setTiket($tiket)
    {
        $this->tiket = $tiket;
    }

    /**
     * @return null
     */
    public function getSTiket()
    {
        return $this->s_tiket;
    }

    /**
     * @param null $s_tiket
     */
    public function setSTiket($s_tiket)
    {
        $this->s_tiket = $s_tiket;
    }

    /**
     * @return null
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * @param null $date_creation
     */
    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
    }




    public function tableShow()
    {
        // TODO: Implement tableShow() method.
    }
}