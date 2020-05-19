<?php
/**
 * Description of Entities
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: Log
 */

class logModel extends Model
{
    function insertLog($objet)
    {
        parent::setClass(logComandes::class);
        return parent::insert($objet);
    }
}