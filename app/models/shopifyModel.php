<?php
/**
 * Description of Entities
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: shopify
 */

class shopifyModel extends Model
{

    function commandeExists($id_commande)
    {
        parent::setClass(shopify::class);
        return $this->selectByCondition("commande = " . $id_commande);
    }

    function ajouter($object)
    {
        parent::setClass(shopify::class);
        return parent::insert($object);
    }
}